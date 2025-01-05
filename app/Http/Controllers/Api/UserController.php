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
use App\Notifications\NewFollowerNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function show(User $user) {
        return SuccessResponse::send('User Profile', UserProfileResource::make($user));
    }

    public function update(UpdateProfileRequest $request, User $user, GenerateUniqueUsername $username) {
        $data = $request->validated();
        if(isset($data['password'])) {
            $data['password'] = HASH::make($data['password']);
        }
        if(isset($data['name'])) {
            $data['username'] = $username($data['name']);
        }
        $user->update($data);
        return SuccessResponse::send('User Profile Updated', UserProfileResource::make($user));
    }

    public function follow(User $userToFollow) {
        $user = auth()->user();
        if($user->followings()->where('following_id', $userToFollow->id)->exists()) {
            throw ValidationException::withMessages(['Already Followed!']);
        }
        $user->followings()->attach($userToFollow);
        $userToFollow->notify(new NewFollowerNotification($user->name, $user->username));
        return SuccessResponse::send('User Followed Successfully');
    }

    public function unfollow(User $userToUnfollow) {
        $user = auth()->user();
        if(!$user->followings()->where('following_id', $userToUnfollow->id)->exists()) {
            throw ValidationException::withMessages(['Already Unfollowed!']);
        }
        $user->followings()->detach($userToUnfollow);
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
