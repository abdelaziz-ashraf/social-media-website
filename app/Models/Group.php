<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description' , 'auto_approval'];

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function users()
    {
        return $this->hasMany(GroupUser::class);
    }
}
