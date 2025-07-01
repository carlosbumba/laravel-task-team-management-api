<?php

namespace Auth\Infrastructure\Persistence\Model;

use Shared\Domain\Enums\UserRole;
use Auth\Infrastructure\Persistence\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Task\Infrastructure\Persistence\Model\Task;
use Team\Infrastructure\Persistence\Model\Team;

class User extends Authenticatable
{
    use HasFactory, HasUlids, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    /**
     * Gera token Sanctum para o usuário.
     *
     * @param string $name
     * @return string
     */
    public function generateToken($name = 'auth_token'): string
    {
        return $this->createToken($name)->plainTextToken;
    }

    public function hasAnyRole(string ...$roles)
    {
        return in_array($this->role->value, $roles, strict: true);
    }

    public function teams(): BelongsToMany
    {

        return $this->belongsToMany(Team::class, 'team_members')
            ->withPivot('role_in_team')
            ->withTimestamps();
    }
}
