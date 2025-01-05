<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestJoinGroup extends Model
{
    protected $fillable = ['group_id', 'user_id'];

    protected $table = 'group_request_to_join';
}
