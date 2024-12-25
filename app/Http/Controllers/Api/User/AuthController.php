<?php

namespace App\Http\Controllers\Api\User;

use App\Actions\User\GenerateUniqueUsername;
use App\Actions\User\GenerateVerificationCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserRegisterRequest;
use App\Http\Requests\User\Auth\UserLoginRequest;
use App\Http\Requests\User\Auth\VerifyEmailRequest;
use App\Http\Resources\User\Auth\LoggedUserResource;
use App\Http\Resources\User\Auth\RegisteredUserResource;
use App\Http\Responses\SuccessResponse;
use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\VerificationEmailCodeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request, GenerateUniqueUsername $username, GenerateVerificationCode $verificationCode) {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['username'] = $username($data['name']);
        $user = User::create($data);
        $user->notify(new VerificationEmailCodeNotification($verificationCode($user->id)));
        return SuccessResponse::send('User registered successfully, confirm your email to login (check your email).', RegisteredUserResource::make($user));
    }

    public function verifyEmail(VerifyEmailRequest $request) {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if(!$user) {
            throw ValidationException::withMessages(['Check your data .. maybe email, code or both are not valid.']);
        }
        $verificationCode = VerificationCode::where('user_id', $user->id)->where('code', $data['code'])->first();
        if(!$verificationCode) {
            throw ValidationException::withMessages(['Check your data .. maybe email, code or both are not valid.']);
        }
        $user->markEmailAsVerified();
        $verificationCode->delete();
        return SuccessResponse::send('Email verified successfully.');
    }

    public function login(UserLoginRequest $request) {
        $userData = $request->validated();
        $user = User::where('email', $userData['email'])->first();
        if(is_null($user->email_verified_at)) {
            throw ValidationException::withMessages([
                'email' => ['Verify your email address to login.'],
            ]);
        }
        if(!$user || !Hash::check($userData['password'], $user->password)) {
            throw ValidationException::withMessages([
                'error' => 'These credentials do not match our records.',
            ]);
        }
        $user['token'] = $user->createToken('sm-auth-token')->plainTextToken;
        return SuccessResponse::send('User logged in successfully', LoggedUserResource::make($user));
    }
}
