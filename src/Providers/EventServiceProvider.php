<?php

namespace Akrad\Bridage\Providers;

use Akrad\Bridage\Events\DeleteProject;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Akrad\Bridage\Events\Project;
use Akrad\Bridage\Events\UpdateProject;
use Akrad\Bridage\Listeners\ApiAddProject;
use Akrad\Bridage\Listeners\ApiDeleteProject;
use Akrad\Bridage\Listeners\ApiUpdateProject;
use Akrad\Bridage\Models\Helper;
use Akrad\Bridage\Models\Models;
use Akrad\Bridage\Observers\createObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $helper  = Helper::all();

        foreach($helper as $help)
        {
            $model = Models::makeModel($help->object);
            $model::observe(createObserver::class);
        }
    }
}
