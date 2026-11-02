<?php

namespace App\Application\DTOs;

class CreateProjectDTO
{
    public function __construct(
        public int $userId,
        public string $name,
        public ?string $description = null,
        public string $status = 'active',
    ) {}
}
