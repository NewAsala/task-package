<?php

namespace Akrad\Bridage\Events;

use Illuminate\Http\Request;


class UpdateProject
{

    public $event;
    
    public function __construct(Request $event)
    {
        $this->event = $event;
    }

    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
    }
}
