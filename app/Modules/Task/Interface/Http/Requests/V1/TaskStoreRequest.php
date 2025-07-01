<?php

namespace Task\Interface\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\In;
use Task\Domain\Enums\TaskStatus;

class TaskStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'min:5'],
            'description'     => ['required', 'string'],
            'due_time'        => ['required', 'date', 'after_or_equal:today'],
            'status'          => ['sometimes', Rule::in(TaskStatus::values())],

            // Delegação
            'taskable_type'   => ['required', Rule::in(['User', 'Team'])],
            'taskable_id'     => ['required', 'ulid', $this->taskableExistsRule()],
        ];
    }

    /**
     * Retorna uma regra de existência para taskable_id com base em taskable_type.
     */
    protected function taskableExistsRule(): Exists|In
    {
        return match ($this->input('taskable_type')) {
            'User' => Rule::exists('users', 'id'),
            'Team' => Rule::exists('teams', 'id'),
            default => Rule::in([])
        };
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Invalid status. Allowed: ' . implode(', ', TaskStatus::values()),
            'taskable_type.in' => 'The taskable_type must be either "User" or "Team".',
            'taskable_id.exists' => 'The selected taskable_id does not exist in the chosen taskable_type.',
        ];
    }
}

