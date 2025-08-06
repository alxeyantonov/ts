<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class EmailIndexRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'userId' => 'integer|exists:users,id',
        ];
    }
}
