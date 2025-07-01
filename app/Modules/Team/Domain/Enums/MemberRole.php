<?php

namespace Team\Domain\Enums;

enum MemberRole: string
{
    case MANAGER = 'manager';
    case MEMBER = 'member';

    public function is(self $role): bool
    {
        return $this === $role;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
