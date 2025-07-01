<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('tasks.{id}', function ($user, $task) {
    // verificacao
});
