<?php

namespace App\Http\Requests;

use App\Models\PersonData;
use App\Models\SpecialSponsorship;
use App\Rules\CountryCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SpecialSponsorshipRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'payer_email' => ['required', 'string', 'email'],
            'payer_first_name' => ['required', 'string', 'max:255'],
            'payer_last_name' => ['required', 'string', 'max:255'],
            'payer_gender' => ['required', Rule::in(PersonData::GENDERS)],
            'payer_address' => ['nullable', 'string', 'max:255'],
            'payer_zip_code' => ['nullable', 'string', 'max:255'],
            'payer_city' => ['nullable', 'string', 'max:255'],
            'payer_country' => ['nullable', new CountryCode],
            'type' => ['required', Rule::in(SpecialSponsorship::TYPES)],
            'is_anonymous' => ['boolean'],
            'is_gift' => ['boolean'],
            'is_agreed_to_terms' => ['accepted'],
            'amount' => ['required', 'numeric', 'min:' . SpecialSponsorship::TYPE_AMOUNTS[$this->input('type')]],
        ];

        if ($this->get('is_gift')) {
            $giftRules = [
                'giftee_email' => ['required', 'string', 'email'],
                'giftee_first_name' => ['required', 'string', 'max:255'],
                'giftee_last_name' => ['required', 'string', 'max:255'],
                'giftee_gender' => ['required', Rule::in(PersonData::GENDERS)],
                'giftee_address' => ['nullable', 'string', 'max:255'],
                'giftee_zip_code' => ['nullable', 'string', 'max:255'],
                'giftee_city' => ['nullable', 'string', 'max:255'],
                'giftee_country' => ['nullable', new CountryCode],
                'gift_message' => ['nullable', 'string', 'max:500'],
                'gift_notes' => ['nullable', 'string', 'max:500'],
                'requested_duration' => [
                    'nullable',
                    'numeric',
                    'min:1',
                    'max:' . config('validation.integer_max'),
                ],
            ];

            $rules = array_merge($rules, $giftRules);
        }

        return $rules;
    }

    /**
     * @inheritDoc
     */
    protected function failedValidation(Validator $validator)
    {
        Log::warning(
            'Validation failed @ SpecialSponsorshipRequest',
            [
                'errors' => $validator->errors(),
                'input' => $this->all(),
            ]
        );

        parent::failedValidation($validator);
    }
}
