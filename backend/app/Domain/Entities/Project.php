<?php

namespace App\Domain\Entities;

class Project
{
    public function __construct(
        public ?int $id,
        public int $userId,
        public string $name,
        public ?string $description,
        public string $status = 'active',
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
