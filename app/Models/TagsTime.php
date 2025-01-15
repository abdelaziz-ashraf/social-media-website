<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagsTime extends Model
{

    protected $fillable = ['tag_id', 'day_date', 'count'];

    public function tag() {
        return $this->belongsTo(Tag::class);
    }
}
