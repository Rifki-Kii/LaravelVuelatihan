<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\DTOs\CreateProjectDTO;
use App\Application\DTOs\UpdateProjectDTO;
use App\Application\UseCases\Projects\CreateProjectUseCase;
use App\Application\UseCases\Projects\DeleteProjectUseCase;
use App\Application\UseCases\Projects\ListProjectsUseCase;
use App\Application\UseCases\Projects\UpdateProjectUseCase;
use App\Domain\Entities\Project;
use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Http\Requests\V1\StoreProjectRequest;
use App\Presentation\Http\Requests\V1\UpdateProjectRequest;
use App\Presentation\Http\Resources\V1\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct(
        private ListProjectsUseCase $listProjects,
        private CreateProjectUseCase $createProject,
        private UpdateProjectUseCase $updateProject,
        private DeleteProjectUseCase $deleteProject,
    ) {}

    public function index(Request $request)
    {
        $projects = $this->listProjects->execute(Auth::id());

        return response()->json([
            'data' => ProjectResource::collection(collect($projects)),
            'message' => 'Daftar project',
        ], 200);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->createProject->execute(new CreateProjectDTO(
            userId: Auth::id(),
            name: $request->validated('name'),
            description: $request->validated('description'),
            status: $request->validated('status') ?? 'active',
        ));

        return response()->json([
            'data' => new ProjectResource($project),
            'message' => 'Project berhasil dibuat',
        ], 201);
    }

    public function show(int $project)
    {
        $resource = new ProjectResource($this->findProject($project));

        return response()->json([
            'data' => $resource,
            'message' => 'Detail project',
        ], 200);
    }

    public function update(UpdateProjectRequest $request, int $project)
    {
        $updated = $this->updateProject->execute($project, new UpdateProjectDTO(
            name: $request->validated('name') ?? null,
            description: $request->validated('description') ?? null,
            status: $request->validated('status') ?? null,
        ));

        return response()->json([
            'data' => new ProjectResource($updated),
            'message' => 'Project berhasil diperbarui',
        ], 200);
    }

    public function destroy(int $project)
    {
        $this->deleteProject->execute($project);

        return response()->json([
            'data' => null,
            'message' => 'Project berhasil dihapus',
        ], 200);
    }

    private function findProject(int $id): Project
    {
        $project = app(ProjectRepositoryInterface::class)->findById($id);

        if (! $project) {
            abort(404, 'Project tidak ditemukan');
        }

        return $project;
    }
}
