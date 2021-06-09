<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(): bool
    {
        return Auth::check();
    }

    public function update(User $user, Message $message)
    {
        return $user->id === $message->user_id;
    }

    public function destroy(User $user, Message $message)
    {
        return $user->id === $message->user_id;
    }
}
