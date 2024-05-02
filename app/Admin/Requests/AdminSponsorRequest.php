<?php

namespace App\Admin\Requests;

use App\Models\PersonData;
use App\Rules\CountryCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminSponsorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {

        return [
            'email' => ['required', 'string', 'email'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(PersonData::GENDERS)],
            'is_gender_exception' => ['boolean'],
            'date_of_birth' => ['nullable', 'date', 'before:now'],
            'address' => ['nullable', 'string', 'max:255'],
            'zip_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', new CountryCode],
            'is_active' => ['boolean'],
        ];
    }
}
