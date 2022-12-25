<?php

namespace App\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ];
    }
}
