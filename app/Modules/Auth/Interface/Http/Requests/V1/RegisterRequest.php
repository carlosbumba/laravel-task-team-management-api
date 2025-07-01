<?php

namespace Auth\Interface\Http\Requests\V1;

use Shared\Domain\Enums\UserRole;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:8', 'max:255', 'regex:@^[\pL\s\-]+$@u', 'unique:users'],
            'role' => ['required', 'in:' . implode(',', UserRole::values())],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::min(8)->mixedCase()->numbers()]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.min' => 'The name must be at least :min characters.',
            'name.max' => 'The name must not exceed :max characters.',
            'name.regex' => 'The name may only contain letters, spaces, and hyphens.',
            'name.unique' => 'The chosen name is already in use.',

            'role.required' => 'The role field is required.',
            'role.in' => 'The selected role is invalid.',

            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email must not exceed :max characters.',
            'email.unique' => 'This email address is already registered.',

            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least :min characters.',
            'password.mixedCase' => 'The password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'The password must contain at least one number.',
        ];
    }
}
