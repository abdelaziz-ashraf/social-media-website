<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'post_id', 'content', 'parent_id'];

    protected static function booted()
    {
        static::created(function ($comment) {
            $comment->post->increment('number_of_comments');
        });

        static::deleted(function ($comment) {
            $comment->post->decrement('number_of_comments');
        });
    }
    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function parent () {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function childern() {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
