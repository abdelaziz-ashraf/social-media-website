<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Str;

class GenerateUniqueUsername {

    public function __invoke(string $name) : string
    {
        $slug = Str::slug($name);
        return $slug . (User::where('username', $slug)->count() ?: '');
    }
}
