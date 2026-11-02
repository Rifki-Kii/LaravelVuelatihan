<?php

namespace App\Domain\Entities;

class Comment
{
    public function __construct(
        public ?int $id,
        public int $taskId,
        public int $userId,
        public string $body,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
