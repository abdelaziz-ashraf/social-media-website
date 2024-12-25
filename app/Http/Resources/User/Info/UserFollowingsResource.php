<?php

namespace App\Http\Resources\User\Info;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFollowingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::where('id', $this['following_id'])->first();
        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'username' => $this['username'],
            'created_at' => $this['created_at'],
        ];
    }
}
