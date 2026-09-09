<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class InviteMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'email' => [
                'required',
                'email',
                'exists:users,email',
                function ($attribute, $value, $fail) use ($project) {
                    if ($project->owner->email === $value) {
                        $fail('Kamu adalah pemilik project ini.');
                    }
                    if ($project->members()->where('email', $value)->exists()) {
                        $fail('Pengguna ini sudah menjadi anggota project.');
                    }
                },
            ],
        ];
    }
}