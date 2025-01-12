<?php

namespace App\Http\Requests\Post;

use App\Models\GroupUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\UnauthorizedException;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $groupId = $this->input('group_id');
        if(isset($groupId) &&
            ! GroupUser::where('group_id', $groupId)
            ->where('user_id', auth()->id())->exists()
        ) {
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
            'content' => 'required|string',
            'group_id' => 'nullable|integer|exists:groups,id'
        ];
    }
}
