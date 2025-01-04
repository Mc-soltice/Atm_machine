<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => "required|string|max:64",
            'last_name' => "required|string|max:64",
            'email' => "required|email|max:150",
            'password' => "required|string|confirmed|min:8",
            'role_id' => "required|exists:roles,id|numeric"
        ];
    }
}
