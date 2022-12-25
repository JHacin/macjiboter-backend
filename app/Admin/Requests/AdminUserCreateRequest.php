<?php

namespace App\Admin\Requests;

use App\Models\PersonData;
use App\Rules\CountryCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUserCreateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'unique:' . config('permission.table_names.users', 'users') . ',email'
            ],
            'name' => ['required'],
            'password' => ['required', 'confirmed'],
            'should_send_welcome_email' => ['boolean']
        ];
    }
}
