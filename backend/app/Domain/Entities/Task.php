<?php

namespace App\Domain\Entities;

class Task
{
    public function __construct(
        public ?int $id,
        public int $projectId,
        public int $userId,
        public string $title,
        public ?string $description,
        public string $status = 'todo',
        public string $priority = 'medium',
        public ?\DateTimeImmutable $dueDate = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
