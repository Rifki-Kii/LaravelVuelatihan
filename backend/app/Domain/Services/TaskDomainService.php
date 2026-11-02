<?php

namespace App\Domain\Services;

use App\Domain\Entities\Task;
use App\Domain\Events\TaskCompleted;
use App\Domain\Events\TaskCreated;

class TaskDomainService
{
    public const STATUS_TODO = 'todo';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_REVIEW = 'review';

    public const STATUS_DONE = 'done';

    public const PRIORITY_LOW = 'low';

    public const PRIORITY_MEDIUM = 'medium';

    public const PRIORITY_HIGH = 'high';

    public const PRIORITY_URGENT = 'urgent';

    public function isValidStatus(string $status): bool
    {
        return in_array($status, [self::STATUS_TODO, self::STATUS_IN_PROGRESS, self::STATUS_REVIEW, self::STATUS_DONE], true);
    }

    public function isValidPriority(string $priority): bool
    {
        return in_array($priority, [self::PRIORITY_LOW, self::PRIORITY_MEDIUM, self::PRIORITY_HIGH, self::PRIORITY_URGENT], true);
    }

    public function canTransitionTo(string $current, string $next): bool
    {
        if (! $this->isValidStatus($next)) {
            return false;
        }

        return match ($current) {
            self::STATUS_TODO => in_array($next, [self::STATUS_IN_PROGRESS, self::STATUS_DONE], true),
            self::STATUS_IN_PROGRESS => in_array($next, [self::STATUS_REVIEW, self::STATUS_DONE], true),
            self::STATUS_REVIEW => in_array($next, [self::STATUS_IN_PROGRESS, self::STATUS_DONE], true),
            self::STATUS_DONE => false,
            default => false,
        };
    }

    public function createTaskEvent(Task $task): TaskCreated
    {
        return new TaskCreated($task);
    }

    public function completeTaskEvent(Task $task): TaskCompleted
    {
        return new TaskCompleted($task);
    }
}
