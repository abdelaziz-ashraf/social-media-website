<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'username' => $this->user->username,
            'content' => $this['content'],
            'children' => CommentResource::collection($this->childern),
            'created_at' => $this['created_at'],
        ];
    }
}
