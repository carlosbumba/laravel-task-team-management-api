<?php

namespace Team\Domain\Entities;

use Illuminate\Support\Carbon;
use Team\Domain\Enums\MemberRole;

class TeamMember
{
    public function __construct(
        public string|null $id = null,
        public string $team_id,
        public string $user_id,
        public MemberRole|string $role_in_team,
        public Carbon|null $created_at = null,
        public Carbon|null $updated_at = null,
    ) {}
}
