<?php

namespace Team\Infrastructure\Persistence\Factories;

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Team\Infrastructure\Persistence\Model\TeamMember;
use Team\Infrastructure\Persistence\Model\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Team\Infrastructure\Persistence\Model\TeamMember>
 */
class TeamMembersFactory extends Factory
{
    protected $model = TeamMember::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'user_id' => User::factory(),
            'role_in_team' => $this->faker->randomElement(['member', 'manager']),
        ];
    }

    public function manager(): static
    {
        return $this->state(['role_in_team' => 'manager']);
    }

    public function member(): static
    {
        return $this->state(['role_in_team' => 'member']);
    }
}
