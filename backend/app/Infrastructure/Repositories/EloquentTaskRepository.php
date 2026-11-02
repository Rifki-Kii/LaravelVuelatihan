<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Models\Task as EloquentTask;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function findById(int $id): ?Task
    {
        $model = EloquentTask::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findByProject(int $projectId): array
    {
        return array_map(
            fn ($m) => $this->toEntity($m),
            EloquentTask::where('project_id', $projectId)->orderBy('created_at', 'desc')->get()->all(),
        );
    }

    public function findByUser(int $userId): array
    {
        return array_map(
            fn ($m) => $this->toEntity($m),
            EloquentTask::where('user_id', $userId)->orderBy('created_at', 'desc')->get()->all(),
        );
    }

    public function create(array $data): Task
    {
        $model = EloquentTask::create($data);

        return $this->toEntity($model);
    }

    public function update(Task $task, array $data): Task
    {
        $model = EloquentTask::find($task->id);
        $model->update($data);

        return $this->toEntity($model->fresh());
    }

    public function delete(Task $task): bool
    {
        $model = EloquentTask::find($task->id);

        return $model ? (bool) $model->delete() : false;
    }

    private function toEntity(EloquentTask $model): Task
    {
        return new Task(
            id: $model->id,
            projectId: (int) $model->project_id,
            userId: (int) $model->user_id,
            title: $model->title,
            description: $model->description,
            status: $model->status,
            priority: $model->priority,
            dueDate: $model->due_date ? \DateTimeImmutable::createFromFormat('Y-m-d', $model->due_date) : null,
            createdAt: $model->created_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at) : null,
            updatedAt: $model->updated_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->updated_at) : null,
        );
    }
}
