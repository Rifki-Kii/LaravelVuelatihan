<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Models\User as EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $model = EloquentUser::find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $model = EloquentUser::where('email', $email)->first();

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): User
    {
        $model = EloquentUser::create($data);

        return $this->toEntity($model);
    }

    public function update(User $user, array $data): User
    {
        $model = EloquentUser::find($user->id);
        $model->update($data);

        return $this->toEntity($model->fresh());
    }

    public function delete(User $user): bool
    {
        $model = EloquentUser::find($user->id);

        return $model ? (bool) $model->delete() : false;
    }

    private function toEntity(EloquentUser $model): User
    {
        return new User(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: $model->password,
            rememberToken: $model->remember_token,
            createdAt: $model->created_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->created_at) : null,
            updatedAt: $model->updated_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->updated_at) : null,
        );
    }
}
