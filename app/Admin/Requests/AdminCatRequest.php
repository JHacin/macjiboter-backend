<?php

namespace App\Admin\Requests;

use App\Models\Cat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminCatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'gender' => ['required_if:is_group,0', 'nullable', Rule::in(Cat::GENDERS)],
            'status' => ['required', Rule::in(Cat::STATUSES)],
            'date_of_birth' => ['nullable', 'date', 'before:now'],
            'date_of_arrival_mh' => ['nullable', 'date', 'before:now', 'after_or_equal:date_of_birth'],
            'story' => ['nullable', 'string'],
            'is_group' => ['boolean'],
            'is_published' => ['boolean'],
            'crud_photos_array.*.caption' => ['nullable', 'string', 'max:100'],
            'crud_photos_array.*.url' => ['required', 'string'],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'gender.required_if' => 'Spol je obvezen, če ne gre za skupino muc.',
            'crud_photos_array.url.required' => 'Slika je obvezna v dodanem polju. Če slike ne želite uporabiti, oz. jo želite odstraniti, kliknite na gumb "X" levo od okvirja.',
        ];
    }
}
