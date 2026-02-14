<?php

use Illuminate\Support\Facades\Route;
use ba6ul\Razorpay\Http\Controllers\Shop\RazorpayController; 

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {

    // 2. Ensure this says 'redirect' to match your Controller's public function redirect()
    Route::get('razorpay-redirect', [RazorpayController::class, 'process'])->name('razorpay.process');
    
    // 3. Ensure this says 'verify' to match your Controller's public function verify()
    Route::post('razorpaycheck', [RazorpayController::class, 'verify'])->name('razorpay.callback');

});