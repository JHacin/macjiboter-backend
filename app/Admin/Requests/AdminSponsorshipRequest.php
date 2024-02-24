<?php

namespace App\Admin\Requests;

use App\Models\Sponsorship;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminSponsorshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        return [
            'is_anonymous' => ['boolean'],
            'is_gift' => ['boolean'],
            'monthly_amount' => [
                'required',
                'numeric',
                'min:'.config('money.donation_minimum'),
                'max:'.config('validation.integer_max'),
            ],
            'payment_type' => ['required', Rule::in(Sponsorship::PAYMENT_TYPES)],
            'cat' => [
                'required',
                'integer',
                Rule::exists('cats', 'id'),
            ],
            'sponsor' => [
                'required',
                'integer',
                Rule::exists('person_data', 'id'),
                Rule::unique('sponsorships', 'sponsor_id')
                    ->where('is_active', true)
                    ->where('cat_id', $this->input('cat'))
                    ->ignore($this->get('id')),
            ],
            'payer' => [
                'required_if:is_gift,1',
                'nullable',
                'integer',
                Rule::exists('person_data', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'sponsor.unique' => 'Muca že ima aktivnega botra s tem email naslovom.',
            'payer.unique' => 'Muca že ima aktivnega botra s tem email naslovom.',
            'payer.required_if' => 'Plačnik je obvezen če je botrstvo podarjeno.',
        ];
    }
}
