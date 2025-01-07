<?php

namespace App\Http\Controllers\Api;

use App\Actions\User\GenerateUniqueUsername;
use App\Actions\User\GenerateVerificationCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\UserLoginRequest;
use App\Http\Requests\User\Auth\UserRegisterRequest;
use App\Http\Requests\User\Auth\VerifyEmailRequest;
use App\Http\Resources\User\Auth\LoggedUserResource;
use App\Http\Resources\User\Auth\RegisteredUserResource;
use App\Http\Responses\SuccessResponse;
use App\Services\AuthService;

class AuthController extends Controller
{

    protected $authService;
    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(
        UserRegisterRequest $request,
        GenerateUniqueUsername $generateUniqueUsername,
        GenerateVerificationCode $generateVerificationCode
    ) {
        $data = $request->validated();
        $user = $this->authService->register($data, $generateUniqueUsername, $generateVerificationCode);
        return SuccessResponse::send('User registered successfully, confirm your email to login (check your email).', RegisteredUserResource::make($user));
    }

    public function verifyEmail(VerifyEmailRequest $request) {
        $data = $request->validated();
        $this->authService->verifyEmail($data['email'], $data['code']);
        return SuccessResponse::send('Email verified successfully.');
    }

    public function login(UserLoginRequest $request) {
        $userData = $request->validated();
        $user = $this->authService->login($userData->email, $userData->password);
        return SuccessResponse::send('User logged in successfully', LoggedUserResource::make($user));
    }
}
