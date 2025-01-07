<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'name' => (string) $this['name'],
            'description' => (string) $this['description'],
            'auto_approval' => (boolean) $this['auto_approval'],
            'created_at' => $this['created_at'],
        ];
    }
}
