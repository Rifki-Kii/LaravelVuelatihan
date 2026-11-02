<?php

namespace App\Application\DTOs;

class UpdateProjectDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $description = null,
        public ?string $status = null,
    ) {}
}
