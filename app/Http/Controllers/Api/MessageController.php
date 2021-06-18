<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Message\IndexRequest;
use App\Http\Requests\Api\Message\StoreRequest;
use App\Http\Requests\Api\Message\UpdateRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    function index(IndexRequest $request)
    {
        $messages = Message::orderBy('id', 'DESC')
            ->filter($request->validated())
            ->with('user', 'likedUsers')
            ->cursorPaginate(10);

        return MessageResource::collection($messages);
    }

    function store(StoreRequest $request)
    {
        $user = Auth::user();
        $message = $request->makeMessage();
        $message->user()->associate($user);
        $message->save();

        return MessageResource::make($message);
    }

    function update(UpdateRequest $request, Message $message)
    {
        $message->update($request->validated());

        return MessageResource::make($message);
    }

    function destroy(Message $message)
    {
        return response()->json($message->delete());
    }
}
