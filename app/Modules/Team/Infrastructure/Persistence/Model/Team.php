<?php

namespace Team\Infrastructure\Persistence\Model;

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Task\Infrastructure\Persistence\Model\Task;
use Team\Infrastructure\Persistence\Factories\TeamFactory;

class Team extends Model
{
    use HasUlids, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name'
    ];

    protected static function newFactory(): TeamFactory
    {
        return TeamFactory::new();
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot('role_in_team')
            ->withTimestamps();
    }

    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function managers(): BelongsToMany
    {
        return $this->users()->wherePivot('role_in_team', 'manager');
    }
}
