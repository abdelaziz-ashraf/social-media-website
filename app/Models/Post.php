<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['content', 'user_id', 'group_id', 'number_of_likes', 'number_of_comments'];

    public function toSearchableArray() {
        return [
            'content' => $this->content,
        ];
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function tags () {
        return $this->hasMany(PostTag::class);
    }
}
