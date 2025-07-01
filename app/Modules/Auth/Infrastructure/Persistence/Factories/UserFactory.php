<?php

namespace Auth\Infrastructure\Persistence\Factories;

use Shared\Domain\Enums\UserRole;
use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Auth\Infrastructure\Persistence\Model\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * The current role being used by the factory.
     */
    protected static ?string $role;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10)
        ];

        $data['role'] = static::$role ??= fake()->randomElement(UserRole::values());
        $data['password'] = static::$password ??= Hash::make('password123');

        return $data;
    }


    /**
     * Define o estado do usuário como administrador (ADMIN).
     *
     * @return static
     */
    public function admin(): static
    {
        return $this->state(fn() => ['role' => UserRole::ADMIN->value]);
    }

    /**
     * Define o estado do usuário como gerente (MANAGER).
     *
     * @return static
     */
    public function manager(): static
    {
        return $this->state(fn() => ['role' => UserRole::MANAGER->value]);
    }

    /**
     * Define o estado do usuário como membro comum (MEMBER).
     *
     * @return static
     */
    public function member(): static
    {
        return $this->state(fn() => ['role' => UserRole::MEMBER->value]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
