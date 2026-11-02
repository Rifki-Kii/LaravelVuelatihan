<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Application\DTOs\CreateCommentDTO;
use App\Application\UseCases\Comments\CreateCommentUseCase;
use App\Application\UseCases\Comments\ListCommentsUseCase;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Http\Requests\V1\StoreCommentRequest;
use App\Presentation\Http\Resources\V1\CommentResource;

class CommentController extends Controller
{
    public function __construct(
        private ListCommentsUseCase $listComments,
        private CreateCommentUseCase $createComment,
        private CommentRepositoryInterface $comments,
    ) {}

    public function index(int $task)
    {
        $comments = $this->listComments->execute($task);

        return response()->json([
            'data' => CommentResource::collection($comments),
            'message' => 'Daftar komentar',
        ], 200);
    }

    public function store(StoreCommentRequest $request)
    {
        $validated = $request->validated();

        $comment = $this->createComment->execute(new CreateCommentDTO(
            taskId: (int) $validated['task_id'],
            userId: auth()->id(),
            body: $validated['body'],
        ));

        return response()->json([
            'data' => new CommentResource($comment),
            'message' => 'Komentar berhasil ditambahkan',
        ], 201);
    }

    public function destroy(int $comment)
    {
        $model = $this->comments->findById($comment);

        if (! $model) {
            abort(404, 'Komentar tidak ditemukan');
        }

        $this->comments->delete($model);

        return response()->json([
            'data' => null,
            'message' => 'Komentar berhasil dihapus',
        ], 200);
    }
}
