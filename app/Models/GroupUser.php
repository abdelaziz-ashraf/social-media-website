<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    protected $fillable = ['user_id', 'group_id', 'role'];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
