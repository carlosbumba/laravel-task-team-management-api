<?php

namespace Team\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Team\Infrastructure\Persistence\Model\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Team\Infrastructure\Persistence\Model\Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Team'
        ];
    }
}
