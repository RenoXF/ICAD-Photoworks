<?php

namespace App\Providers;

use App\Enums\RoleEnum;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        View::composer('*', function ($view) {
            $view->with('user', auth()->user());
            $view->with('isOwner', auth()->user()?->role === RoleEnum::Owner);
            $view->with('isAdmin', auth()->user()?->role === RoleEnum::Admin);
            $view->with('isClient', auth()->user()?->role === RoleEnum::Client);
        });

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        // Set you client key
        \Midtrans\Config::$clientKey = config('services.midtrans.clientKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');
        // Set notification url
        \Midtrans\Config::$appendNotifUrl = url('/midtrans-callback', secure: true);

        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
