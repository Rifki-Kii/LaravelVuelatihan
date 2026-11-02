<?php

namespace App\Application\UseCases\Tasks;

use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Domain\Services\TaskDomainService;
use Exception;
use InvalidArgumentException;

class ChangeTaskStatusUseCase
{
    public function __construct(
        private TaskRepositoryInterface $tasks,
        private TaskDomainService $domainService,
    ) {}

    public function execute(int $id, string $status): Task
    {
        $task = $this->tasks->findById($id);

        if (! $task) {
            throw new Exception('Tugas tidak ditemukan');
        }

        if (! $this->domainService->canTransitionTo($task->status, $status)) {
            throw new InvalidArgumentException('Transisi status tidak diperbolehkan');
        }

        return $this->tasks->update($task, ['status' => $status]);
    }
}
