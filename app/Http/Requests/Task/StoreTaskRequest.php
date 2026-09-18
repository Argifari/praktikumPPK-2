<?php
namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Otorisasi dapat disesuaikan dengan Policy
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
            'status' => 'nullable|in:pending,in_progress,completed',
            'created_by' => 'required|exists:users,id'
        ];
    }
}