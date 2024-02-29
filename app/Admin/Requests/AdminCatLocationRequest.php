<?php

namespace App\Admin\Requests;

use App\Rules\CountryCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminCatLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('cat_locations', 'name')->ignore($this->get('id'))],
            'address' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', new CountryCode],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'To ime že uporablja obstoječa lokacija.',
        ];
    }
}
