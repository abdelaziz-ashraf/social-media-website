<?php

namespace App\Http\Resources\Group;

use App\Http\Resources\Post\PostResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupDetailsResource extends JsonResource
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
            'description' => $this['description'],
            'auto_approval' => $this['auto_approval'],
            'posts' => PostResource::collection($this->posts),
            'created' => $this['created'],
        ];
    }
}
