<?php

namespace App\Domain\Events;

use App\Domain\Entities\Task;

class TaskCreated
{
    public function __construct(public Task $task) {}
}
