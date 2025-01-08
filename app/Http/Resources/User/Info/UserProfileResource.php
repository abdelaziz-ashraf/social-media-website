<?php

namespace App\Http\Resources\User\Info;

use App\Http\Resources\Post\PostResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (integer) $this['id'],
            'name' => $this['name'],
            'username' => $this['username'],
            'email' => $this['email'],
            'numberOfFollowers' => $this->followers()->count(),
            'numberOfFollowings' => $this->followings()->count(),
            'badges' => $this->badges,
            'posts' => PostResource::collection($this['posts']),
            'created_at' => $this['created_at'],
        ];
    }
}
