<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function postTag() {
        return $this->hasOne(PostTag::class);
    }

    public function TagTime() {
        return $this->hasOne(TagsTime::class);
    }
}
