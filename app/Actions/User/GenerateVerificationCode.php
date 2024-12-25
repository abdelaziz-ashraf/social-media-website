<?php

namespace App\Actions\User;

use App\Models\VerificationCode;
use Illuminate\Support\Str;

class GenerateVerificationCode {

    public function __invoke($user_id) {
        $code = Str::random(6);
        VerificationCode::create([
            'user_id' => $user_id,
            'code' => $code
        ]);
        return $code;
    }
}
