<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Task;

interface TaskRepositoryInterface
{
    public function findById(int $id): ?Task;

    public function findByProject(int $projectId): array;

    public function findByUser(int $userId): array;

    public function create(array $data): Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): bool;
}
