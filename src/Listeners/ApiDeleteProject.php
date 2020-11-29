<?php

namespace Akrad\Bridage\Listeners;


use Akrad\Bridage\Events\DeleteProject;
use GuzzleHttp\Client;

class ApiDeleteProject
{
    public function __construct()
    {
        //
    }

    public function handle(DeleteProject $event)
    {
        $this->updateStatusProject($event->event);
    }

    public function updateStatusProject($event)
    {
        dd("dle");
        
    }
}
