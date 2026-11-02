<?php

namespace App\Application\UseCases\Projects;

use App\Domain\Entities\Project;
use App\Domain\Repositories\ProjectRepositoryInterface;

class ListProjectsUseCase
{
    public function __construct(private ProjectRepositoryInterface $projects) {}

    public function execute(int $userId): array
    {
        return $this->projects->findByUser($userId);
    }

    /**
     * @return Project[]
     */
    public function __invoke(int $userId): array
    {
        return $this->execute($userId);
    }
}
