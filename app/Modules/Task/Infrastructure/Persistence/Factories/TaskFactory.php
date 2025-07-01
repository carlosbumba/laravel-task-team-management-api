<?php

namespace Task\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Task\Infrastructure\Persistence\Model\Task;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Task\Infrastructure\Persistence\Model\Task>
 */

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title'           => $this->faker->sentence(4),
            'description'     => $this->faker->paragraph,
            'due_time'        => now()->addDays(rand(1, 10))->toDateString(),
            'status'          => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'taskable_id'     => Str::ulid()->toBase32(), // sobrescreva ao usar
            'taskable_type'   => 'User', // ou 'Team' — sobrescreva no uso
        ];
    }
}
