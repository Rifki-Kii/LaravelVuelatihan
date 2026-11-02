<?php

namespace App\Domain\Events;

use App\Domain\Entities\Task;

class TaskCompleted
{
    public function __construct(public Task $task) {}
}
