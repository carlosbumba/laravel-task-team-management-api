<?php

namespace User\Interface\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $currentUserId = $this->user()->id;

        return [
            'name' => ['nullable', 'regex:@^[\pL\s\-]+$@u', Rule::unique('users')->ignore($currentUserId)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($currentUserId)]
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

            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email must not exceed :max characters.',
            'email.unique' => 'This email address is already registered.',
        ];
    }
}
