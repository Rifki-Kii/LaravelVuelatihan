<?php

namespace App\Presentation\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', 'string', 'in:todo,in_progress,review,done'],
            'priority' => ['sometimes', 'required', 'string', 'in:low,medium,high,urgent'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'tag_ids' => ['sometimes', 'array'],
            'tag_ids.*' => ['sometimes', 'integer', 'exists:tags,id'],
        ];
    }
}
