<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (App::environment(['production', 'PRODUCTION'])) {
            $this->app['request']->server->set('HTTPS', true);
            URL::forceScheme('https');
        }
        
        if (app()->environment(['production', 'PRODUCTION'])) {
            $this->app['request']->server->set('HTTPS', true);
            URL::forceScheme('https');
        }
        Paginator::useBootstrap();
    }
}
