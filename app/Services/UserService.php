<?php

namespace App\Services;

use App\Actions\User\GenerateUniqueUsername;
use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function update (array $data, GenerateUniqueUsername $generateUniqueUsername) : User {
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

    public function followUser(User $userToFollow) {
        $user = auth()->user();
        if($user->followings()->where('following_id', $userToFollow->id)->exists()) {
            throw ValidationException::withMessages(['Already Followed!']);
        }
        $user->followings()->attach($userToFollow);
        $userToFollow->notify(new NewFollowerNotification($user->name, $user->username));
    }


    public function unfollowUser(User $userToUnfollow) {
        $user = auth()->user();
        if(!$user->followings()->where('following_id', $userToUnfollow->id)->exists()) {
            throw ValidationException::withMessages(['Already Unfollowed!']);
        }
        $user->followings()->detach($userToUnfollow);
    }

    public function uploadAvatar($avatar) {
        $fileName = 'avatars/' . uniqid() . $avatar->getClientOriginalExtension();
        Storage::disk('backblaze')->put($fileName, file_get_contents($avatar));

        $user = auth()->user();
        if ($user->avatar) {
            Storage::disk('backblaze')->delete($user->avatar);
        }
        $user->avatar = $fileName;
        $user->save();

        return $user;
    }
}
