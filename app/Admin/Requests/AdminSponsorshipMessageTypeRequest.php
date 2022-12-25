<?php

namespace App\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminSponsorshipMessageTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sponsorship_message_types', 'name'),
            ],
            'subject' => ['required', 'string', 'max:255'],
            'template_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sponsorship_message_types', 'template_id'),
            ],
            'is_active' => ['boolean']
        ];
    }

    public function attributes(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Izbrano ime je že v uporabi.',
            'template_id.unique' => 'Izbrana šifra predloge je že v uporabi.',
        ];
    }
}
