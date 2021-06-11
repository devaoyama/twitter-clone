<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Like\DestroyRequest;
use App\Http\Requests\Api\Like\StoreRequest;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(StoreRequest $request)
    {
        $user = Auth::user();
        $user->likedMessages()->attach($request->message_id);
        return response()->json(1);
    }

    public function destroy(DestroyRequest $request)
    {
        $user = Auth::user();
        $user->likedMessages()->detach($request->message_id);
        return response()->json(1);
    }
}
