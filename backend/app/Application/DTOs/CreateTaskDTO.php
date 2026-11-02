<?php

namespace App\Application\DTOs;

class CreateTaskDTO
{
    public function __construct(
        public int $projectId,
        public int $userId,
        public string $title,
        public ?string $description = null,
        public string $status = 'todo',
        public string $priority = 'medium',
        public ?\DateTimeImmutable $dueDate = null,
        public array $tagIds = [],
    ) {}
}
