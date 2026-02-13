<?php

use Illuminate\Support\Facades\Route;
use ba6ul\Razorpay\Http\Controllers\Shop\RazorpayController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'razorpay'], function () {
    Route::get('', [RazorpayController::class, 'index'])->name('shop.razorpay.index');
});