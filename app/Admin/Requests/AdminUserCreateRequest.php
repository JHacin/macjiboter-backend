<?php

namespace App\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'unique:'.config('permission.table_names.users', 'users').',email',
            ],
            'name' => ['required'],
            'password' => ['required', 'confirmed'],
            'should_send_welcome_email' => ['boolean'],
        ];
    }
}
