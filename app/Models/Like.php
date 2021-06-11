<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'likes';

    protected $primaryKey = ['user_id', 'message_id'];

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'message_id',
    ];

    public $timestamps = false;
}
