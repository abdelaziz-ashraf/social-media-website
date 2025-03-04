<?php
declare(strict_types=1);
namespace App\Services;

use App\Actions\User\GenerateUniqueUsername;
use App\Actions\User\GenerateVerificationCode;
use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\VerificationEmailCodeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(string $email, string $password) : User {
        $user = User::where('email', $email)->first();
        if(is_null($user->email_verified_at)) {
            throw ValidationException::withMessages([
                'email' => ['Verify your email address to login.'],
            ]);
        }
        if(!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'error' => 'These credentials do not match our records.',
            ]);
        }
        $user['token'] = $user->createToken('sm-auth-token')->plainTextToken;
        return $user;
    }

    public function register(array $data, GenerateUniqueUsername $generateUniqueUsername, GenerateVerificationCode $generateVerificationCode) : User {
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $generateUniqueUsername($data['name']);
        $user = User::create($data);
        $user->notify(new VerificationEmailCodeNotification($generateVerificationCode($user->id)));
        return $user;
    }

    public function verifyEmail(string $email, string $verificationCode) : void {
        $user = User::where('email', $email)->first();
        if(!$user) {
            throw ValidationException::withMessages(['Check your data .. maybe email, code or both are not valid.']);
        }

        $verificationCode = VerificationCode::where('user_id', $user->id)
            ->where('code', $verificationCode)->first();

        if(!$verificationCode) {
            throw ValidationException::withMessages(['Check your data .. maybe email, code or both are not valid.']);
        }

        $user->markEmailAsVerified();
        $verificationCode->delete();
    }
}
