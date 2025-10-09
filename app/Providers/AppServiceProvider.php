<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        if ($this->app->environment('production')) {
        $this->app->register(\Sentry\Laravel\ServiceProvider::class);
//        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->environment(['staging', 'production']) && !(request()->routeIs(['login', 'comcerts.show']))) {
            URL::forceScheme('https');
        }
    }
}
