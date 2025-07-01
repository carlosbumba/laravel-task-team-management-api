<?php

namespace Auth\Interface\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i', 'exists:users'],
            'password' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'The email field is required.',
            'email.regex' => 'Please provide a valid email address.',
            'email.exists' => 'No user found with this email address.',
            'password.required' => 'The password field is required.'
        ];
    }
}
