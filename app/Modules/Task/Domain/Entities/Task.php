<?php

namespace Task\Domain\Entities;

use DateTime;

class Task
{
    public function __construct(
        public string $title,
        public string $description,
        public string $due_time,
        public ?string $status = null,
        public ?string $taskable_id = null,
        public ?string $taskable_type = null,
        public ?string $id = null,
        public string|DateTime|null $created_at = null,
        public string|DateTime|null $updated_at = null
    ) {}
}
