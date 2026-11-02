<?php

namespace App\Application\DTOs;

class CreateCommentDTO
{
    public function __construct(
        public int $taskId,
        public int $userId,
        public string $body,
    ) {}
}
