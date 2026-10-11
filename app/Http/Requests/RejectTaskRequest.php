<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manager_note' => ['required', 'string'],
        ];
    }
}
