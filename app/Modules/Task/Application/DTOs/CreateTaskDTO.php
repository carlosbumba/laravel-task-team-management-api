<?php

namespace Task\Application\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $due_time,
        public string $status,
        public string $user_id,
        public string $taskable_id,
        public string $taskable_type,
    ) {}

    public static function fromRequest(FormRequest $request, string $userId): self
    {

        return new self(
            title: $request->input('title'),
            description: $request->input('description'),
            due_time: $request->input('due_time'),
            status: $request->input('status'),
            user_id: $userId,
            taskable_id: $request->input('taskable_id'),
            taskable_type: $request->input('taskable_type')
        );
    }
}

