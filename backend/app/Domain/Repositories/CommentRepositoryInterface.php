<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Comment;

interface CommentRepositoryInterface
{
    public function findById(int $id): ?Comment;

    public function findByTask(int $taskId): array;

    public function create(array $data): Comment;

    public function delete(Comment $comment): bool;
}
