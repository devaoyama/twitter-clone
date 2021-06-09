<?php

namespace App\Http\Controllers;

use App\Models\Message;

class DefaultController extends Controller
{
    public function index()
    {
        $messages = Message::orderBy('id', 'DESC')->cursorPaginate(10);

        return view('default.index', compact('messages'));
    }
}
