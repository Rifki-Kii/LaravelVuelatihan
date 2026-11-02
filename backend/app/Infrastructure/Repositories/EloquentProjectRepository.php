<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Project;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Models\Project as EloquentProject;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function findById(int $id): ?Project
    {
        $model = EloquentProject::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findByUser(int $userId): array
    {
        return array_map(
            fn ($m) => $this->toEntity($m),
            EloquentProject::where('user_id', $userId)->orderBy('created_at', 'desc')->get()->all(),
        );
    }

    public function create(array $data): Project
    {
        $model = EloquentProject::create($data);

        return $this->toEntity($model);
    }

    public function update(Project $project, array $data): Project
    {
        $model = EloquentProject::find($project->id);
        $model->update($data);

        return $this->toEntity($model->fresh());
    }

    public function delete(Project $project): bool
    {
        $model = EloquentProject::find($project->id);

        return $model ? (bool) $model->delete() : false;
    }

    private function toEntity(EloquentProject $model): Project
    {
        return new Project(
            id: $model->id,
            userId: (int) $model->user_id,
            name: $model->name,
            description: $model->description,
            status: $model->status,
            createdAt: $model->created_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at) : null,
            updatedAt: $model->updated_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->updated_at) : null,
        );
    }
}
