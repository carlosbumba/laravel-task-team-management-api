<?php

namespace Task\Application\DTOs;

use Illuminate\Foundation\Http\FormRequest;
use Task\Infrastructure\Persistence\Model\Task as ModelTask;

class UpdateTaskDTO
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public string $due_time,
        public string $status
    ) {}

    public static function fromModelWithRequest(ModelTask $task, FormRequest $request): self
    {
        return new self(
            id: $task->id,
            title: $request->input('title', $task->title),
            description: $request->input('description', $task->description),
            due_time: $request->input('due_time', $task->due_time),
            status: $request->input('status', $task->status)
        );
    }
}
