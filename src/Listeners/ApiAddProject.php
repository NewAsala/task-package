<?php

namespace Akrad\Bridage\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Akrad\Bridage\Events\Project;
use GuzzleHttp\Client;

class ApiAddProject
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Project $event)
    {
        $this->updateStatusProject($event->event);
    }

    public function updateStatusProject($event)
    {
        // $event ->viewer = $event ->viewer +1;
        // $event ->save();
        $params = [
            'query' => [
               'name' => $event->title
            ]
         ];

        $client = new Client();
        $response = $client->request('GET','http://localhost:8083/spaceStoreTest',$params);
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        dd($statusCode);
    }
}
