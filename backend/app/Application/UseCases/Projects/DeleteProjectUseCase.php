<?php

namespace App\Application\UseCases\Projects;

use App\Domain\Repositories\ProjectRepositoryInterface;
use Exception;

class DeleteProjectUseCase
{
    public function __construct(private ProjectRepositoryInterface $projects) {}

    public function execute(int $id): bool
    {
        $project = $this->projects->findById($id);

        if (! $project) {
            throw new Exception('Project tidak ditemukan');
        }

        return $this->projects->delete($project);
    }
}
