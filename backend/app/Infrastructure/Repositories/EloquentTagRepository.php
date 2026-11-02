<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Tag;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Models\Tag as EloquentTag;
use App\Models\Task;

class EloquentTagRepository implements TagRepositoryInterface
{
    public function findById(int $id): ?Tag
    {
        $model = EloquentTag::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function all(): array
    {
        return array_map(
            fn ($m) => $this->toEntity($m),
            EloquentTag::orderBy('name', 'asc')->get()->all(),
        );
    }

    public function create(array $data): Tag
    {
        $model = EloquentTag::create($data);

        return $this->toEntity($model);
    }

    public function attachToTask(int $taskId, int $tagId): void
    {
        Task::find($taskId)?->tags()->syncWithoutDetaching([$tagId]);
    }

    public function detachFromTask(int $taskId, int $tagId): void
    {
        Task::find($taskId)?->tags()->detach($tagId);
    }

    public function findByTask(int $taskId): array
    {
        return array_map(
            fn ($m) => $this->toEntity($m),
            EloquentTag::whereHas('tasks', fn ($q) => $q->where('task_id', $taskId))->get()->all(),
        );
    }

    private function toEntity(EloquentTag $model): Tag
    {
        return new Tag(
            id: $model->id,
            name: $model->name,
            color: $model->color,
            createdAt: $model->created_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at) : null,
            updatedAt: $model->updated_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->updated_at) : null,
        );
    }
}
