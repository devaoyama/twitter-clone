<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function isLiked(User $user): bool
    {
        return $this->likedUsers()->where('user_id', $user->id)->exists();
    }

    static public function scopeFilter(Builder $query, array $conditions): Builder
    {
        foreach ($conditions as $key => $condition) {
            $query->where($key, $condition);
        }
        return $query;
    }
}
