<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;

class MessageController extends Controller
{
    function index()
    {
        $messages = Message::orderBy('id', 'DESC')->with('user', 'likedUsers')->cursorPaginate(10);

        return MessageResource::collection($messages);
    }
}
