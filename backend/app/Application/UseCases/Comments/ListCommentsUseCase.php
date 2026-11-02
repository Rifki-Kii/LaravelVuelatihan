<?php

namespace App\Application\UseCases\Comments;

use App\Domain\Entities\Comment;
use App\Domain\Repositories\CommentRepositoryInterface;

class ListCommentsUseCase
{
    public function __construct(private CommentRepositoryInterface $comments) {}

    /**
     * @return Comment[]
     */
    public function execute(int $taskId): array
    {
        return $this->comments->findByTask($taskId);
    }
}
