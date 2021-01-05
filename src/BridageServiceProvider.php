<?php

namespace Akrad\Bridage;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Akrad\Bridage\Providers\EventServiceProvider;
use Akrad\Bridage\Providers\AppServiceProvider;

class BridageServiceProvider extends ServiceProvider
{
    public function boot()
    {
      Schema::defaultStringLength(191);
      $this->loadRoutesFrom(__DIR__.'/routes/web.php');
      $this->loadViewsFrom(__DIR__.'/views','bridage');
      $this->loadMigrationsFrom(__DIR__.'/database/migrations');

      $this->publishes([
        __DIR__ . '/public/js' => public_path('assets/js'),
      ], 'public');

    }

    public function register()
    {
      $this->app->register(EventServiceProvider::class);
      $this->app->register(AppServiceProvider::class);
    }
}