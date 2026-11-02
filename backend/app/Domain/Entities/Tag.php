<?php

namespace App\Domain\Entities;

class Tag
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $color = '#3b82f6',
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
