<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Comment;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Models\Comment as EloquentComment;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function findById(int $id): ?Comment
    {
        $model = EloquentComment::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findByTask(int $taskId): array
    {
        return array_map(
            fn ($m) => $this->toEntity($m),
            EloquentComment::where('task_id', $taskId)->orderBy('created_at', 'asc')->get()->all(),
        );
    }

    public function create(array $data): Comment
    {
        $model = EloquentComment::create($data);

        return $this->toEntity($model);
    }

    public function delete(Comment $comment): bool
    {
        $model = EloquentComment::find($comment->id);

        return $model ? (bool) $model->delete() : false;
    }

    private function toEntity(EloquentComment $model): Comment
    {
        return new Comment(
            id: $model->id,
            taskId: (int) $model->task_id,
            userId: (int) $model->user_id,
            body: $model->body,
            createdAt: $model->created_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at) : null,
            updatedAt: $model->updated_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->updated_at) : null,
        );
    }
}
