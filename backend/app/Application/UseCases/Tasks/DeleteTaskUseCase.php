<?php

namespace App\Application\UseCases\Tasks;

use App\Domain\Repositories\TaskRepositoryInterface;
use Exception;

class DeleteTaskUseCase
{
    public function __construct(private TaskRepositoryInterface $tasks) {}

    public function execute(int $id): bool
    {
        $task = $this->tasks->findById($id);

        if (! $task) {
            throw new Exception('Tugas tidak ditemukan');
        }

        return $this->tasks->delete($task);
    }
}
