<?php

namespace Akrad\Bridage\Listeners;


use Akrad\Bridage\Events\UpdateProject;
use GuzzleHttp\Client;

class ApiUpdateProject
{
    public function __construct()
    {
        //
    }

    public function handle(UpdateProject $event)
    {
        $this->updateStatusProject($event->event);
    }

    public function updateStatusProject($event)
    {
        dd("update");
        
    }
}
