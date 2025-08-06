<?php

namespace App\Http\Requests\Api;

use App\Models\Email;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'userId' => 'required|integer|exists:users,id',
            'email' => [
                'required',
                'string',
                'max:255',
                'email',
                Rule::unique(Email::class, 'email')->where('user_id', $this->input('userId'))
            ],
        ];
    }
}
