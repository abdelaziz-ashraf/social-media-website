<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\UnauthorizedException;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        if(request()->route('comment')->user_id !== auth()->id()) {
            return false;
        }
        return true;
    }

    protected function failedAuthorization() {
        throw new UnauthorizedException;
    }

    public function rules(): array
    {
        return [
            'content' => 'string',
        ];
    }
}
