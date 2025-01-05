<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id'];

    protected static function booted()
    {
        static::created(function ($like) {
            $like->post->increment('number_of_likes');
        });

        static::deleted(function ($like) {
            $like->post->decrement('number_of_likes');
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function post() {
        return $this->belongsTo(Post::class);
    }
}
