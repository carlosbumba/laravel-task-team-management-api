<?php

namespace Team\Application\DTOs;

use Illuminate\Foundation\Http\FormRequest;
use Team\Domain\Entities\TeamMember;

class TeamMemberDTO
{
    public function __construct(
        public string|null $id = null,
        public string $user_id,
        public string $team_id,
        public string $role_in_team,
    ) {}

    public static function fromRequest(FormRequest $request, $teamId = null)
    {
        return new self(id: null, user_id: $request->user_id, team_id: $teamId, role_in_team: $request->role_in_team);
    }

    public function toEntity(): TeamMember
    {
        return new TeamMember($this->id, $this->team_id, $this->user_id, $this->role_in_team);
    }
}
