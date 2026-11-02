<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\DTOs\CreateTaskDTO;
use App\Application\DTOs\UpdateTaskDTO;
use App\Application\UseCases\Tasks\ChangeTaskStatusUseCase;
use App\Application\UseCases\Tasks\CreateTaskUseCase;
use App\Application\UseCases\Tasks\DeleteTaskUseCase;
use App\Application\UseCases\Tasks\ListTasksUseCase;
use App\Application\UseCases\Tasks\UpdateTaskUseCase;
use App\Domain\Entities\Task;
use App\Domain\Repositories\TaskRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Http\Requests\V1\StoreTaskRequest;
use App\Presentation\Http\Requests\V1\UpdateTaskRequest;
use App\Presentation\Http\Resources\V1\TaskCollection;
use App\Presentation\Http\Resources\V1\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct(
        private ListTasksUseCase $listTasks,
        private CreateTaskUseCase $createTask,
        private UpdateTaskUseCase $updateTask,
        private DeleteTaskUseCase $deleteTask,
        private ChangeTaskStatusUseCase $changeTaskStatus,
        private TaskRepositoryInterface $tasks,
    ) {}

    public function index(Request $request)
    {
        $projectId = (int) $request->query('project_id', 0);

        $tasks = $projectId
            ? $this->listTasks->execute($projectId)
            : $this->tasks->findByUser(Auth::id());

        return response()->json([
            'data' => new TaskCollection(collect($tasks)),
            'message' => 'Daftar tugas',
        ], 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->createTask->execute(new CreateTaskDTO(
            projectId: (int) $request->validated('project_id'),
            userId: Auth::id(),
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: $request->validated('status') ?? 'todo',
            priority: $request->validated('priority') ?? 'medium',
            dueDate: $request->validated('due_date') ? \DateTimeImmutable::createFromFormat('Y-m-d', $request->validated('due_date')) : null,
            tagIds: $request->validated('tag_ids') ?? [],
        ));

        return response()->json([
            'data' => new TaskResource($task),
            'message' => 'Tugas berhasil dibuat',
        ], 201);
    }

    public function show(int $task)
    {
        $resource = new TaskResource($this->findTask($task));

        return response()->json([
            'data' => $resource,
            'message' => 'Detail tugas',
        ], 200);
    }

    public function update(UpdateTaskRequest $request, int $task)
    {
        $updated = $this->updateTask->execute($task, new UpdateTaskDTO(
            title: $request->validated('title') ?? null,
            description: $request->validated('description') ?? null,
            status: $request->validated('status') ?? null,
            priority: $request->validated('priority') ?? null,
            dueDate: $request->validated('due_date') ? \DateTimeImmutable::createFromFormat('Y-m-d', $request->validated('due_date')) : null,
        ));

        return response()->json([
            'data' => new TaskResource($updated),
            'message' => 'Tugas berhasil diperbarui',
        ], 200);
    }

    public function destroy(int $task)
    {
        $this->deleteTask->execute($task);

        return response()->json([
            'data' => null,
            'message' => 'Tugas berhasil dihapus',
        ], 200);
    }

    public function changeStatus(Request $request, int $task)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:todo,in_progress,review,done'],
        ]);

        $updated = $this->changeTaskStatus->execute($task, $validated['status']);

        return response()->json([
            'data' => new TaskResource($updated),
            'message' => 'Status tugas berhasil diubah',
        ], 200);
    }

    private function findTask(int $id): Task
    {
        $task = $this->tasks->findById($id);

        if (! $task) {
            abort(404, 'Tugas tidak ditemukan');
        }

        return $task;
    }
}
