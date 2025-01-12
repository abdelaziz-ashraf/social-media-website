<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = ['follower_id', 'following_id'];
    public function following() {
        return $this->belongsTo(User::class, 'following_id');
    }

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    protected static function booted() {
        static::created(function ($follow) {
            $follow->following->increment('number_of_followers');
            $follow->follower->increment('number_of_followings');
        });

        static::deleted(function ($unfollow) {
            $unfollow->following->decrement('number_of_followers');
            $unfollow->follower->decrement('number_of_followings');
        });
    }
}
