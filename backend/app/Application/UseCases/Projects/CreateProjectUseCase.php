<?php

namespace App\Application\UseCases\Projects;

use App\Application\DTOs\CreateProjectDTO;
use App\Domain\Entities\Project;
use App\Domain\Repositories\ProjectRepositoryInterface;

class CreateProjectUseCase
{
    public function __construct(private ProjectRepositoryInterface $projects) {}

    public function execute(CreateProjectDTO $dto): Project
    {
        return $this->projects->create([
            'user_id' => $dto->userId,
            'name' => $dto->name,
            'description' => $dto->description,
            'status' => $dto->status,
        ]);
    }
}
