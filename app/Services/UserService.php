<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function update ($data, $generateUniqueUsername) {
        $user = auth()->user();
        if(isset($data['password'])) {
            $data['password'] = HASH::make($data['password']);
        }
        if(isset($data['name'])) {
            $data['username'] = $generateUniqueUsername($data['name']);
        }
        $user->update($data);
        return $user;
    }

    public function followUser($userToFollow) {
        $user = auth()->user();
        if($user->followings()->where('following_id', $userToFollow->id)->exists()) {
            throw ValidationException::withMessages(['Already Followed!']);
        }
        $user->followings()->attach($userToFollow);
        $userToFollow->notify(new NewFollowerNotification($user->name, $user->username));
    }


    public function unfollowUser($userToUnfollow) {
        $user = auth()->user();
        if(!$user->followings()->where('following_id', $userToUnfollow->id)->exists()) {
            throw ValidationException::withMessages(['Already Unfollowed!']);
        }
        $user->followings()->detach($userToUnfollow);
    }
}
