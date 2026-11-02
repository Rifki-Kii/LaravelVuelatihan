<?php

namespace App\Presentation\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCollection extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data' => ProjectResource::collection($this->collection),
        ];
    }
}
