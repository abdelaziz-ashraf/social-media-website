<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'user' => $this->user->name,
            'group' => $this['group_id'] ? $this->group->name : null,
            'content' => $this['content'],
            'numberOfLikes' => $this->likes()->count(),
            'numberOfComments' => $this->comments()->count(),
            'created_at' => $this['created_at'],
        ];
    }
}
