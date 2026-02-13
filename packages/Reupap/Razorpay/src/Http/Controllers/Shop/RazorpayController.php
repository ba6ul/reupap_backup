<?php

namespace Reupap\Razorpay\Http\Controllers\Shop;

use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;

class RazorpayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('razorpay::shop.index');
    }
}
