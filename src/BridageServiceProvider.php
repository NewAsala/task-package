<?php

namespace Akrad\Bridage;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Akrad\Bridage\Providers\EventServiceProvider;

class BridageServiceProvider extends ServiceProvider
{
    public function boot()
    {
      Schema::defaultStringLength(191);
      $this->loadRoutesFrom(__DIR__.'/routes/web.php');
      $this->loadViewsFrom(__DIR__.'/views','bridage');
      $this->loadMigrationsFrom(__DIR__.'/database/migrations');

    }

    public function register()
    {
      $this->app->register(EventServiceProvider::class);
    }
}