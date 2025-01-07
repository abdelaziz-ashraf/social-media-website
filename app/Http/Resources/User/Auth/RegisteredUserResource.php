<?php

namespace App\Http\Resources\User\Auth;

use App\Http\Requests\User\Auth\UserLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisteredUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (integer) $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at
        ];
    }

}
