<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'lastname' => ['sometimes', 'string', 'max:255'],
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($this->route('id')),
            ],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('id')),
            ],
            'dui' => [
                'sometimes',
                'regex:/^[0-9]{8}-[0-9]$/',
                Rule::unique('users', 'dui')->ignore($this->route('id')),
            ],
            'phone_number' => ['nullable', 'regex:/^[0-9+\-\s]+$/'],
            'birth_date' => ['sometimes', 'date', 'before:today'],
            'hiring_date' => ['sometimes', 'date'],
        ];
    }
}