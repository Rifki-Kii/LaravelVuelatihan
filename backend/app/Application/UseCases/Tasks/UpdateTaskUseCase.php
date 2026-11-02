<?php

namespace App\Application\UseCases\Tasks;

use App\Application\DTOs\UpdateTaskDTO;
use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Domain\Services\TaskDomainService;
use Exception;
use InvalidArgumentException;

class UpdateTaskUseCase
{
    public function __construct(
        private TaskRepositoryInterface $tasks,
        private TaskDomainService $domainService,
    ) {}

    public function execute(int $id, UpdateTaskDTO $dto): Task
    {
        $task = $this->tasks->findById($id);

        if (! $task) {
            throw new Exception('Tugas tidak ditemukan');
        }

        $data = [];

        if ($dto->title !== null) {
            $data['title'] = $dto->title;
        }

        if ($dto->description !== null) {
            $data['description'] = $dto->description;
        }

        if ($dto->status !== null) {
            if (! $this->domainService->isValidStatus($dto->status)) {
                throw new InvalidArgumentException('Status tugas tidak valid');
            }
            $data['status'] = $dto->status;
        }

        if ($dto->priority !== null) {
            if (! $this->domainService->isValidPriority($dto->priority)) {
                throw new InvalidArgumentException('Prioritas tugas tidak valid');
            }
            $data['priority'] = $dto->priority;
        }

        if ($dto->dueDate !== null) {
            $data['due_date'] = $dto->dueDate->format('Y-m-d');
        }

        return $this->tasks->update($task, $data);
    }
}
