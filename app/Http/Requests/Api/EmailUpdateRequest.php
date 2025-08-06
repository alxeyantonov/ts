<?php

namespace App\Http\Requests\Api;

use App\Models\Email;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var Email $email */
        $email = $this->route('email');
        return [
            'email' => [
                'required',
                'string',
                'max:255',
                'email',
                Rule::unique(Email::class, 'email')
                    ->where('user_id', $email->user_id)
                    ->ignoreModel($email)
            ],
        ];
    }
}
