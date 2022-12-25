<?php

namespace App\Http\Requests;

use App\Models\PersonData;
use App\Rules\CountryCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CatSponsorshipRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'payer_email' => ['required', 'string', 'email'],
            'payer_first_name' => ['required', 'string', 'max:255'],
            'payer_last_name' => ['required', 'string', 'max:255'],
            'payer_gender' => ['required', Rule::in(PersonData::GENDERS)],
            'payer_address' => ['required', 'string', 'max:255'],
            'payer_zip_code' => ['required', 'string', 'max:255'],
            'payer_city' => ['required', 'string', 'max:255'],
            'payer_country' => ['required', new CountryCode],
            'monthly_amount' => [
                'required',
                'numeric',
                'min:' . config('money.donation_minimum'),
                'max:' . config('validation.integer_max'),
            ],
            'is_anonymous' => ['boolean'],
            'is_gift' => ['boolean'],
            'is_agreed_to_terms' => ['accepted'],
            'wants_direct_debit' => ['boolean'],
        ];

        if ($this->get('is_gift')) {
            $giftRules = [
                'giftee_email' => ['required', 'string', 'email'],
                'giftee_first_name' => ['required', 'string', 'max:255'],
                'giftee_last_name' => ['required', 'string', 'max:255'],
                'giftee_gender' => ['required', Rule::in(PersonData::GENDERS)],
                'giftee_address' => ['required', 'string', 'max:255'],
                'giftee_zip_code' => ['required', 'string', 'max:255'],
                'giftee_city' => ['required', 'string', 'max:255'],
                'giftee_country' => ['required', new CountryCode],
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
            'Validation failed @ CatSponsorshipRequest',
            [
                'errors' => $validator->errors(),
                'input' => $this->all(),
            ]
        );

        parent::failedValidation($validator);
    }
}
