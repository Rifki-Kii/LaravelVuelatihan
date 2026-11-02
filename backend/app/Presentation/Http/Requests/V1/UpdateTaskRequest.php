<?php

namespace App\Presentation\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'required', 'string', 'in:todo,in_progress,review,done'],
            'priority' => ['sometimes', 'required', 'string', 'in:low,medium,high,urgent'],
            'due_date' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
