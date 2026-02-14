<!DOCTYPE html>
<html>
<head>
    <title>Processing Payment...</title>
    <style>
        .loader { 
            text-align: center; 
            margin-top: 100px; 
            font-family: sans-serif; 
        }
    </style>
</head>
<body>
    <div class="loader">
        <h2>Please do not refresh the page.</h2>
        <p>Connecting to secure payment gateway...</p>
    </div>

    <form id="razorpay-form" action="{{ $callbackUrl }}" method="POST">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "{{ $keyId }}",
            "amount": "{{ $amount }}",
            "currency": "{{ $currency }}",
            "name": "{{ $name }}",
            "description": "{{ $description }}",
            "image": "{{ $image }}",
            "order_id": "{{ $razorpayOrderId }}",
            "handler": function (response){
                // Fill the hidden form with Razorpay response
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                
                // Submit to your callback controller
                document.getElementById('razorpay_form').submit();
            },
            "prefill": {
                "name": "{{ $cart->customer_first_name }} {{ $cart->customer_last_name }}",
                "email": "{{ $cart->customer_email }}",
                "contact": "{{ $cart->billing_address->phone }}"
            },
            "theme": {
                "color": "#3399cc"
            },
            "modal": {
                "ondismiss": function(){
                    window.location.href = "{{ route('shop.checkout.cart.index') }}";
                }
            }
        };
        var rzp1 = new Razorpay(options);
        
        window.onload = function() {
            rzp1.open();
        };
    </script>
</body>
</html>