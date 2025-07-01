<?php

namespace Team\Application\DTOs;

use Illuminate\Foundation\Http\FormRequest;
use Team\Domain\Entities\Team;

class TeamDTO
{
    public function __construct(
        public string|null $id = null,
        public string $name,
    ) {}

    public static function fromRequest(FormRequest $request, $id = null)
    {
        return new self(id: $id, name: $request->name);
    }

    public function toEntity(): Team
    {
        return new Team(
            id: $this->id,
            name: $this->name,
        );
    }
}
