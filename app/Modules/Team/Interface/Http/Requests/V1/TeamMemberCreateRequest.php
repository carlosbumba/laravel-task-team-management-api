<?php

namespace Team\Interface\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Team\Domain\Enums\MemberRole;

class TeamMemberCreateRequest extends FormRequest
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
            'user_id' => ['required', 'ulid', 'exists:users,id'],
            'role_in_team' => ['required', Rule::in(MemberRole::values())],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'The user_id field is required.',
            'user_id.exists'   => 'The selected user_id does not exist.',
            'role_in_team.required' => 'The role_in_team field is required.',
            'role_in_team.in' => 'Invalid role. Allowed values: ' . implode(', ', MemberRole::values()),
        ];
    }
}
