<?php

namespace App\Application\UseCases\Tasks;

use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepositoryInterface;

class ListTasksUseCase
{
    public function __construct(private TaskRepositoryInterface $tasks) {}

    /**
     * @return Task[]
     */
    public function execute(int $projectId): array
    {
        return $this->tasks->findByProject($projectId);
    }
}
