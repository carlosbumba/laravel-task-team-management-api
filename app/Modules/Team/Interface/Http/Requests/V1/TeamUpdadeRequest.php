<?php

namespace Team\Interface\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class TeamUpdadeRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:4', 'max:100', 'regex:@^[\pL\d\s\-]+$@u', Rule::unique('teams')->ignore($this->route('team'))],
        ];
    }

    public function messages()
    {

        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.min' => 'The name must be at least :min characters.',
            'name.max' => 'The name must not exceed :max characters.',
            'name.regex' => 'The name may only contain letters, numbers, spaces, and hyphens.',
            'name.unique' => 'The chosen team name is already in use.',
        ];
    }
}
