<?php

namespace App\Application\Services;

use App\Application\DTOs\CreateProjectDTO;
use App\Application\UseCases\Projects\CreateProjectUseCase;
use App\Application\UseCases\Projects\ListProjectsUseCase;
use App\Domain\Entities\Project;

class ProjectApplicationService
{
    public function __construct(
        private CreateProjectUseCase $createProject,
        private ListProjectsUseCase $listProjects,
    ) {}

    public function create(CreateProjectDTO $dto)
    {
        return $this->createProject->execute($dto);
    }

    /**
     * @return Project[]
     */
    public function list(int $userId): array
    {
        return $this->listProjects->execute($userId);
    }
}
