<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'description' , 'auto-approval'];

    public function posts() {
        return $this->hasMany(Post::class);
    }
}
