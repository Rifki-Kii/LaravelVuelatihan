<?php

namespace App\Domain\Entities;

class User
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public string $password,
        public ?string $rememberToken = null,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}
}
