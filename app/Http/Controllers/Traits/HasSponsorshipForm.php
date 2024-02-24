<?php

namespace App\Http\Controllers\Traits;

use App\Models\PersonData;
use Illuminate\Foundation\Http\FormRequest;

trait HasSponsorshipForm
{
    protected function getPayerFromFormData(FormRequest $request): PersonData
    {
        return $this->updateOrCreatePersonData($this->constructPersonDataFields($request, 'payer'));
    }

    protected function getGifteeFromFormData(FormRequest $request): ?PersonData
    {
        if ($request->input('is_gift') === true) {
            return $this->updateOrCreatePersonData($this->constructPersonDataFields($request, 'giftee'));
        }

        return null;
    }

    protected function constructPersonDataFields(FormRequest $request, string $prefix): array
    {
        return [
            'email' => $request->input($prefix.'_email'),
            'first_name' => $request->input($prefix.'_first_name'),
            'last_name' => $request->input($prefix.'_last_name'),
            'gender' => $request->input($prefix.'_gender'),
            'address' => $request->input($prefix.'_address'),
            'zip_code' => $request->input($prefix.'_zip_code'),
            'city' => $request->input($prefix.'_city'),
            'country' => $request->input($prefix.'_country'),
        ];
    }

    private function updateOrCreatePersonData(array $personDataFormInput): PersonData
    {
        $existing = $this->findPersonWithSameNameAndEmail($personDataFormInput);

        if ($existing instanceof PersonData) {
            $existing->update($personDataFormInput);
            $existing->refresh();

            return $existing;
        }

        return PersonData::create($personDataFormInput);
    }

    private function findPersonWithSameNameAndEmail(array $personDataFormInput): ?PersonData
    {
        return PersonData::where(['email' => $personDataFormInput['email']])
            ->whereRaw("UPPER(first_name) = '".strtoupper($personDataFormInput['first_name'])."'")
            ->whereRaw("UPPER(last_name) = '".strtoupper($personDataFormInput['last_name'])."'")
            ->get()
            ->first();
    }
}
