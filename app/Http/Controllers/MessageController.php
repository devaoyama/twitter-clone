<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreRequest;
use App\Http\Requests\Message\UpdateRequest;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(StoreRequest $request)
    {
        $user = Auth::user();
        $message = $request->makeMessage();
        $message->user()->associate($user);
        $message->save();

        return redirect('/');
    }

    public function edit(Message $message)
    {
        return view('message.edit', compact('message'));
    }

    public function update(UpdateRequest $request, Message $message)
    {
        $message->update($request->validated());
        return redirect('/');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect('/');
    }
}
