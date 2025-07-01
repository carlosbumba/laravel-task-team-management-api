<?php

namespace Team\Domain\Entities;

use Illuminate\Support\Carbon;

class Team
{
    public function __construct(
        public string|null $id = null,
        public string $name,
        public Carbon|null $created_at = null,
        public Carbon|null $updated_at = null,
    ) {}
}
