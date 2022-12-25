<?php

namespace App\Rules;

use App\Utilities\CountryList;
use Illuminate\Contracts\Validation\Rule;

class CountryCode implements Rule
{
    public function passes($attribute, $value): bool
    {
        return array_key_exists($value, CountryList::COUNTRY_NAMES);
    }

    public function message(): string
    {
        return 'Vrednost mora biti država.';
    }
}
