<?php

namespace App\Application\UseCases\Comments;

use App\Application\DTOs\CreateCommentDTO;
use App\Domain\Entities\Comment;
use App\Domain\Repositories\CommentRepositoryInterface;

class CreateCommentUseCase
{
    public function __construct(private CommentRepositoryInterface $comments) {}

    public function execute(CreateCommentDTO $dto): Comment
    {
        return $this->comments->create([
            'task_id' => $dto->taskId,
            'user_id' => $dto->userId,
            'body' => $dto->body,
        ]);
    }
}
