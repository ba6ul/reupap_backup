<?php

use Illuminate\Support\Facades\Route;
use ba6ul\Razorpay\Http\Controllers\Shop\RazorpayController; // <-- Make sure it points to Shop!

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {
    
    Route::get('/razorpay/process', [RazorpayController::class, 'process'])->name('razorpay.process');
    Route::post('/razorpay/callback', [RazorpayController::class, 'callback'])->name('razorpay.callback');

});