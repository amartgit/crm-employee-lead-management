<?php

namespace App\Http\Controllers;
use App\Events\MyEvent;

abstract class Controller
{

    public function broadcastMessage()
    {
        event(new MyEvent('Hello world!'));
        return response()->json(['status' => 'Message broadcasted']);
    }
}
