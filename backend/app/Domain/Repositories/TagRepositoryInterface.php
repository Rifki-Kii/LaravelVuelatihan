<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Tag;

interface TagRepositoryInterface
{
    public function findById(int $id): ?Tag;

    public function all(): array;

    public function create(array $data): Tag;

    public function attachToTask(int $taskId, int $tagId): void;

    public function detachFromTask(int $taskId, int $tagId): void;

    public function findByTask(int $taskId): array;
}
