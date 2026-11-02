<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Domain\Repositories\TagRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Presentation\Http\Resources\V1\TagResource;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(private TagRepositoryInterface $tags) {}

    public function index()
    {
        $tags = $this->tags->all();

        return response()->json([
            'data' => TagResource::collection($tags),
            'message' => 'Daftar tag',
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['sometimes', 'string', 'max:20'],
        ]);

        $tag = $this->tags->create([
            'name' => $validated['name'],
            'color' => $validated['color'] ?? '#3b82f6',
        ]);

        return response()->json([
            'data' => new TagResource($tag),
            'message' => 'Tag berhasil dibuat',
        ], 201);
    }
}
