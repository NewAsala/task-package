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

        /*$params = [
            'query' => [
               'name' => $event->title
            ]
         ];

        $client = new Client();
        $response = $client->request('GET','http://localhost:8083/spaceStoreTest',$params);
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        dd($statusCode);*/

        $params = [
            'query' => [
               'email' => $event->title,
               'password' => $event->group
            ]
        ];

        $client = new Client();
        $response = $client->request('post','http://127.0.0.1:8083/api/auth/login',$params);
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        
        /////////////////////////////////////////////////////

        $token = json_decode($body)->access_token;

        $client1 = new Client(['base_uri' => 'http://127.0.0.1:8083/']);

        $headers = [
            'Authorization' => 'Bearer ' . $token,        
            'Accept'        => 'application/json',
        ];

        $response1 = $client1->request('Post', '/api/auth/me', [
            'headers' => $headers
        ]);

        $body1 = $response1->getBody()->getContents();

        dd($body1);
    }
}
