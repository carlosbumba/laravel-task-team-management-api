<?php

namespace Task\Interface\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Task\Domain\Enums\TaskStatus;

class TaskUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'           => ['sometimes', 'string', 'min:5'],
            'description'     => ['sometimes', 'string'],
            'due_time'        => ['sometimes', 'date', 'after_or_equal:today'],
            'status'          => ['sometimes', Rule::in(TaskStatus::values())]
        ];
    }
}
