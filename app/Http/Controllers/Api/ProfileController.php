<?php

namespace App\Http\Controllers\Api;

use App\Actions\User\GenerateUniqueUsername;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\UpdateProfileRequest;
use App\Http\Resources\User\Info\UserFollowersResource;
use App\Http\Resources\User\Info\UserFollowingsResource;
use App\Http\Resources\User\Info\UserProfileResource;
use App\Http\Responses\SuccessResponse;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function profile(User $user) : JsonResponse {
        return SuccessResponse::send('User Profile', UserProfileResource::make($user));
    }

    public function update(UpdateProfileRequest $request, GenerateUniqueUsername $generateUniqueUsername) : JsonResponse {
        $user = $this->userService->update($request->validated(), $generateUniqueUsername);
        return SuccessResponse::send('User Profile Updated', UserProfileResource::make($user));
    }

    public function follow(User $userToFollow) : JsonResponse{
        $this->userService->followUser($userToFollow);
        return SuccessResponse::send('User Followed Successfully');
    }

    public function unfollow(User $userToUnfollow) : JsonResponse {
        $this->userService->unfollowUser($userToUnfollow);
        return SuccessResponse::send('User Unfollowed Successfully');
    }

    public function followings(User $user) {
        $followings = $user->followings()->get();
        return SuccessResponse::send('User Followings', UserFollowingsResource::collection($followings));
    }

    public function followers(User $user) {
        $followers = $user->followers()->get();
        return SuccessResponse::send('User Followers', UserFollowersResource::collection($followers));
    }

}
