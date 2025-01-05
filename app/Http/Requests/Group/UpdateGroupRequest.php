<?php

namespace App\Http\Requests\Group;

use App\Models\GroupUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\UnauthorizedException;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $owner = GroupUser::where('group_id', request()->route('group')->id)
            ->where('role', 'owner')->first();

        if(is_null($owner) || $owner->user_id !== auth()->id()) {
            return false;
        }
        return true;
    }

    protected function failedAuthorization() {
        throw new UnauthorizedException;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'description' => 'string',
            'auto_approval' => 'boolean',
        ];
    }
}
