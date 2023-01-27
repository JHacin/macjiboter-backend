<?php

namespace App\Admin\Requests;

use App\Models\SpecialSponsorship;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminSpecialSponsorshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(SpecialSponsorship::TYPES)],
            'sponsor' => [
                'required',
                'integer',
                Rule::exists('person_data', 'id'),
            ],
            'confirmed_at' => ['nullable', 'date'],
            'amount' => ['required', 'numeric'],
        ];
    }
}
