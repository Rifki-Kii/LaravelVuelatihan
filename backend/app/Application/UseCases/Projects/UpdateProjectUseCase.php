<?php

namespace App\Application\UseCases\Projects;

use App\Application\DTOs\UpdateProjectDTO;
use App\Domain\Entities\Project;
use App\Domain\Repositories\ProjectRepositoryInterface;
use Exception;

class UpdateProjectUseCase
{
    public function __construct(private ProjectRepositoryInterface $projects) {}

    public function execute(int $id, UpdateProjectDTO $dto): Project
    {
        $project = $this->projects->findById($id);

        if (! $project) {
            throw new Exception('Project tidak ditemukan');
        }

        return $this->projects->update($project, array_filter([
            'name' => $dto->name,
            'description' => $dto->description,
            'status' => $dto->status,
        ], fn ($v) => $v !== null));
    }
}
