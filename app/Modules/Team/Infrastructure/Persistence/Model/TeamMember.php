<?php

namespace Team\Infrastructure\Persistence\Model;

use Auth\Infrastructure\Persistence\Model\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Team\Infrastructure\Persistence\Factories\TeamMembersFactory;

class TeamMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_id',
        'user_id',
        'role_in_team'
    ];

    protected static function newFactory(): TeamMembersFactory
    {
        return TeamMembersFactory::new();
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
