<?php

namespace ba6ul\Razorpay\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class RazorpayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'razorpay');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'razorpay');
        

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('razorpay::admin.layouts.style');
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php',
            'payment_methods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }
}