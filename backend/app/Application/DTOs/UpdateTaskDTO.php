<?php

namespace App\Application\DTOs;

class UpdateTaskDTO
{
    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $status = null,
        public ?string $priority = null,
        public ?\DateTimeImmutable $dueDate = null,
    ) {}
}
