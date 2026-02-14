<?php

namespace ba6ul\Razorpay\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Transformers\OrderResource;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends Controller
{
    protected $orderRepository;
    protected $invoiceRepository;

    public function __construct(OrderRepository $orderRepository, InvoiceRepository $invoiceRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * 1. PREPARE: Send data to Razorpay and open the popup
     */
    public function process()
    {
        $cart = Cart::getCart();
        
        // Safety check: if cart is empty, go back
        if (!$cart) {
            return redirect()->route('shop.checkout.cart.index');
        }

        // Get Keys from Config (Make sure 'reupap_razorpay' matches your system.php!)
        $keyId = core()->getConfigData('sales.payment_methods.ba6ul_razorpay.key_id');
        $keySecret = core()->getConfigData('sales.payment_methods.ba6ul_razorpay.secret');
        
        $api = new Api($keyId, $keySecret);

        // Calculate amount (Razorpay takes amount in paise)
        $amount = (int) ($cart->grand_total * 100);

        $orderData = [
            'receipt'         => (string) $cart->id,
            'amount'          => $amount,
            'currency'        => $cart->cart_currency_code,
            'payment_capture' => 1 // Auto capture payment
        ];

        // Create Order ID at Razorpay
        $razorpayOrder = $api->order->create($orderData);
        $razorpayOrderId = $razorpayOrder['id'];

        // Store this in Laravel Session to verify later
        session(['razorpay_order_id' => $razorpayOrderId]);

        // Prepare data for the View (The Popup)
        $data = [
            'key'             => $keyId,
            'amount'          => $amount,
            'name'            => 'Reupap Store', // Or core()->getCurrentChannel()->name
            'description'     => 'Order #' . $cart->id,
            'order_id'        => $razorpayOrderId,
            'prefill'         => [
                'name'    => $cart->billing_address->first_name . ' ' . $cart->billing_address->last_name,
                'email'   => $cart->billing_address->email,
                'contact' => $cart->billing_address->phone,
            ],
            'theme'           => ['color' => '#3399cc'],
            'callback_url'    => route('razorpay.callback') // IMPORTANT: Must match your Route Name
        ];

        // This assumes you have a view file at src/Resources/views/razorpay-redirect.blade.php
        return view('razorpay::razorpay-redirect', compact('data'));
    }

    /**
     * 2. VERIFY: Handle the response from Razorpay
     */
    public function verify(Request $request)
    {
        $success = true;
        $error = "Payment Failed";

        $keyId = core()->getConfigData('sales.payment_methods.ba6ul_razorpay.key_id');
        $keySecret = core()->getConfigData('sales.payment_methods.ba6ul_razorpay.secret');

        $api = new Api($keyId, $keySecret);

        try {
            // Verify the signature using the Order ID we stored in session
            $attributes = [
                'razorpay_order_id'   => session('razorpay_order_id'),
                'razorpay_payment_id' => $request->input('razorpay_payment_id'),
                'razorpay_signature'  => $request->input('razorpay_signature')
            ];

            $api->utility->verifyPaymentSignature($attributes);
        } catch (SignatureVerificationError $e) {
            $success = false;
            $error = 'Razorpay Error: ' . $e->getMessage();
        }

        if ($success) {
            $cart = Cart::getCart();
            
            // Bagisto 2.x: Convert Cart to Order Data
            $data = (new OrderResource($cart))->jsonSerialize();
            
            // Create the Order in Bagisto Database
            $order = $this->orderRepository->create($data);
            
            // Mark as Processing
            $this->orderRepository->update(['status' => 'processing'], $order->id);

            // Create Invoice
            if ($order->canInvoice()) {
                $this->invoiceRepository->create($this->prepareInvoiceData($order));
            }

            Cart::deActivateCart();
            session()->flash('order_id', $order->id);

            return redirect()->route('shop.checkout.onepage.success');
        } else {
            session()->flash('error', $error);
            return redirect()->route('shop.checkout.cart.index');
        }
    }

    protected function prepareInvoiceData($order)
    {
        $invoiceData = ["order_id" => $order->id];
        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }
        return $invoiceData;
    }
}