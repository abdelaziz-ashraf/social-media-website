<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            "post_id" => (integer) $this['post_id'],
            "tag_id" => (integer) $this['tag_id'],
            "name" => $this['tag']['name'],
        ];
    }
}
