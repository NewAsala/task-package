<?php

namespace Akrad\Bridage\Providers;

use Akrad\Bridage\Events\DeleteProject;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Event;
use Akrad\Bridage\Events\Project;
use Akrad\Bridage\Events\UpdateProject;
use Akrad\Bridage\Listeners\ApiAddProject;
use Akrad\Bridage\Listeners\ApiDeleteProject;
use Akrad\Bridage\Listeners\ApiUpdateProject;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        
        Project::class => [
            ApiAddProject::class,
        ],UpdateProject::class=>[
            ApiUpdateProject::class
        ],DeleteProject::class=>[
            ApiDeleteProject::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
