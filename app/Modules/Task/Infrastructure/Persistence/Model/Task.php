<?php

namespace Task\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Task\Infrastructure\Persistence\Factories\TaskFactory;

class Task extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['title', 'description', 'due_time', 'status', 'taskable_type', 'taskable_id'];

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }
}
