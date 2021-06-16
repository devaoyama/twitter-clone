<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'likedCount' => $this->likedUsers()->count(),
            'isLiked' => Auth::user() && $this->isLiked(Auth::user()),
            'updated_at' => $this->updated_at->format('Y年m月d日 h時i分'),
            'isEdited' => $this->created_at != $this->updated_at,
            'user' => new UserResource($this->user),
        ];
    }
}
