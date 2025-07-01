<?php

namespace Task\Application\DTOs;

use Illuminate\Foundation\Http\FormRequest;

class CreateOwnTaskDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $due_time,
        public string $status,
        public string $taskable_id,
        public string $taskable_type,
    ) {}

    public static function fromRequest(FormRequest $request, string $taskable_type, string $taskable_id): self
    {
        return new self(
            title: $request->input('title'),
            description: $request->input('description'),
            due_time: $request->input('due_time'),
            status: $request->input('status'),
            taskable_id: $taskable_id,
            taskable_type: $taskable_type
        );
    }
}
