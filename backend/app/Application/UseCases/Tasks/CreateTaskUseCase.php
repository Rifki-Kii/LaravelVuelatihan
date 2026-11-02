<?php

namespace App\Application\UseCases\Tasks;

use App\Application\DTOs\CreateTaskDTO;
use App\Domain\Entities\Task;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Domain\Services\TaskDomainService;

class CreateTaskUseCase
{
    public function __construct(
        private TaskRepositoryInterface $tasks,
        private TagRepositoryInterface $tags,
        private TaskDomainService $domainService,
    ) {}

    public function execute(CreateTaskDTO $dto): Task
    {
        if (! $this->domainService->isValidStatus($dto->status)) {
            throw new \InvalidArgumentException('Status tugas tidak valid');
        }

        if (! $this->domainService->isValidPriority($dto->priority)) {
            throw new \InvalidArgumentException('Prioritas tugas tidak valid');
        }

        $task = $this->tasks->create([
            'project_id' => $dto->projectId,
            'user_id' => $dto->userId,
            'title' => $dto->title,
            'description' => $dto->description,
            'status' => $dto->status,
            'priority' => $dto->priority,
            'due_date' => $dto->dueDate?->format('Y-m-d'),
        ]);

        foreach ($dto->tagIds as $tagId) {
            $this->tags->attachToTask($task->id, $tagId);
        }

        return $task;
    }
}
