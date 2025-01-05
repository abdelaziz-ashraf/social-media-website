<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'description' , 'auto_approval'];

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users', 'group_id', 'user_id');
    }
}
