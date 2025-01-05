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
            'numberOfLikes' => $this['number_of_likes'],
            'numberOfComments' => $this['number_of_comments'],
            'created_at' => $this['created_at'],
        ];
    }
}
