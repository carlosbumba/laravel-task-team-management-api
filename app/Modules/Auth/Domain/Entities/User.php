<?php

namespace Auth\Domain\Entities;

use Shared\Domain\Enums\UserRole;
use Illuminate\Support\Carbon;

class User
{
    public function __construct(
        public string|null $id,
        public string $name,
        public UserRole|string $role,
        public string $email,
        public string $password,

        public Carbon|null $created_at = null,
        public Carbon|null $updated_at = null,
    ) {}
}
