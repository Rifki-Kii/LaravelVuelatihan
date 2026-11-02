<?php

namespace App\Providers;

use App\Application\Services\ProjectApplicationService;
use App\Application\UseCases\Comments\CreateCommentUseCase;
use App\Application\UseCases\Comments\ListCommentsUseCase;
use App\Application\UseCases\Projects\CreateProjectUseCase;
use App\Application\UseCases\Projects\DeleteProjectUseCase;
use App\Application\UseCases\Projects\ListProjectsUseCase;
use App\Application\UseCases\Projects\UpdateProjectUseCase;
use App\Application\UseCases\Tasks\ChangeTaskStatusUseCase;
use App\Application\UseCases\Tasks\CreateTaskUseCase;
use App\Application\UseCases\Tasks\DeleteTaskUseCase;
use App\Application\UseCases\Tasks\ListTasksUseCase;
use App\Application\UseCases\Tasks\UpdateTaskUseCase;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Domain\Repositories\TagRepositoryInterface;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Services\TaskDomainService;
use App\Infrastructure\Repositories\EloquentCommentRepository;
use App\Infrastructure\Repositories\EloquentProjectRepository;
use App\Infrastructure\Repositories\EloquentTagRepository;
use App\Infrastructure\Repositories\EloquentTaskRepository;
use App\Infrastructure\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, EloquentCommentRepository::class);
        $this->app->bind(TagRepositoryInterface::class, EloquentTagRepository::class);

        $this->app->singleton(TaskDomainService::class, function () {
            return new TaskDomainService;
        });

        $this->app->singleton(ProjectApplicationService::class, function ($app) {
            return new ProjectApplicationService(
                $app->make(CreateProjectUseCase::class),
                $app->make(ListProjectsUseCase::class),
            );
        });

        $this->app->singleton(CreateProjectUseCase::class, function ($app) {
            return new CreateProjectUseCase($app->make(ProjectRepositoryInterface::class));
        });
        $this->app->singleton(UpdateProjectUseCase::class, function ($app) {
            return new UpdateProjectUseCase($app->make(ProjectRepositoryInterface::class));
        });
        $this->app->singleton(DeleteProjectUseCase::class, function ($app) {
            return new DeleteProjectUseCase($app->make(ProjectRepositoryInterface::class));
        });
        $this->app->singleton(ListProjectsUseCase::class, function ($app) {
            return new ListProjectsUseCase($app->make(ProjectRepositoryInterface::class));
        });

        $this->app->singleton(CreateTaskUseCase::class, function ($app) {
            return new CreateTaskUseCase(
                $app->make(TaskRepositoryInterface::class),
                $app->make(TagRepositoryInterface::class),
                $app->make(TaskDomainService::class),
            );
        });
        $this->app->singleton(UpdateTaskUseCase::class, function ($app) {
            return new UpdateTaskUseCase(
                $app->make(TaskRepositoryInterface::class),
                $app->make(TaskDomainService::class),
            );
        });
        $this->app->singleton(DeleteTaskUseCase::class, function ($app) {
            return new DeleteTaskUseCase($app->make(TaskRepositoryInterface::class));
        });
        $this->app->singleton(ListTasksUseCase::class, function ($app) {
            return new ListTasksUseCase($app->make(TaskRepositoryInterface::class));
        });
        $this->app->singleton(ChangeTaskStatusUseCase::class, function ($app) {
            return new ChangeTaskStatusUseCase(
                $app->make(TaskRepositoryInterface::class),
                $app->make(TaskDomainService::class),
            );
        });

        $this->app->singleton(CreateCommentUseCase::class, function ($app) {
            return new CreateCommentUseCase($app->make(CommentRepositoryInterface::class));
        });
        $this->app->singleton(ListCommentsUseCase::class, function ($app) {
            return new ListCommentsUseCase($app->make(CommentRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
