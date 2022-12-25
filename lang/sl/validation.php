<?php

return [
    'accepted' => 'Polje mora biti obkljukano.',
    'active_url' => 'Vrednost ni veljavna spletna povezava.',
    'after' => 'Vrednost mora biti datum po :date.',
    'after_or_equal' => 'Vrednost mora biti datum po ali enak kot :date.',
    'alpha' => 'Polje lahko vsebuje le črke.',
    'alpha_dash' => 'Polje lahko vsebuje le črke, številke, podčrtaje in vezaje.',
    'alpha_num' => 'Polje lahko vsebuje le črke in številke.',
    'array' => 'Polje mora biti niz.',
    'before' => 'Vrednost mora biti datum pred :date.',
    'before_or_equal' => 'Vrednost mora biti datum pred ali enak :date.',
    'between' => [
        'numeric' => 'Vrednost mora biti med :min in :max.',
        'file' => 'Polje mora imeti velikost med :min in :max kilobajtov.',
        'string' => 'Polje mora imeti med :min in :max znakov.',
        'array' => 'Polje mora imeti med :min in :max vrednosti.',
    ],
    'boolean' => 'Vrednost mora biti "da" ali "ne".',
    'confirmed' => 'To in potrditveno polje se ne ujemata po vrednosti.',
    'date' => 'Vrednost ni veljaven datum.',
    'date_equals' => 'Vrednost mora biti datum enak :date.',
    'date_format' => 'Vrednost se ne ujema s formatom :format.',
    'different' => 'Polji :attribute in :other ne smeta imeti enakih vrednosti.',
    'digits' => 'Polje mora vsebovati :digits številk.',
    'digits_between' => 'Polje mora vsebovati med :min in :max številk.',
    'dimensions' => 'Slika ima nepravilne dimenzije.',
    'distinct' => 'Polje :attribute ima podvojeno vrednost.',
    'email' => 'Vrednost mora biti veljaven email naslov.',
    'ends_with' => 'Vrednost se mora končati z enim od naslednjih: :values.',
    'exists' => 'Označena vrednost ni pravilna.',
    'file' => 'Polje mora vsebovati datoteko.',
    'filled' => 'Polje mora vsebovati vrednost.',
    'gt' => [
        'numeric' => 'Vrednost mora biti višja od :value.',
        'file' => 'Datoteka mora biti večja od :value kilobajtov.',
        'string' => 'Polje mora vsebovati vsaj :value znakov.',
        'array' => 'Polje mora vsebovati vsaj :value vrednosti.',
    ],
    'gte' => [
        'numeric' => 'Vrednost mora biti vsaj :value.',
        'file' => 'Datoteka mora biti velika vsaj :value kilobajtov.',
        'string' => 'Polje mora vsebovati vsaj :value znakov.',
        'array' => 'Polje mora vsebovati vsaj :value vrednosti.',
    ],
    'image' => 'Polje mora vsebovati slika.',
    'in' => 'Izbrana vrednost ni veljavna.',
    'in_array' => 'Vrednost :attribute ne obstaja v :other.',
    'integer' => 'Vrednost mora biti številka.',
    'ip' => 'Vrednost mora biti veljaven IP naslov.',
    'ipv4' => 'Vrednost mora biti veljaven IPv4 naslov.',
    'ipv6' => 'Vrednost mora biti veljaven IPv6 naslov.',
    'json' => 'Vrednost mora biti veljaven JSON objekt.',
    'lt' => [
        'numeric' => 'Vrednost mora biti manjša od :value.',
        'file' => 'Datoteka mora imeti velikost manj kot :value kilobajtov.',
        'string' => 'Polje mora imeti manj kot :value znakov.',
        'array' => 'Polje mora vsebovati manj kot :value vrednosti.',
    ],
    'lte' => [
        'numeric' => 'Vrednost mora biti enaka :value ali manj.',
        'file' => 'Datoteka mora biti velika :value kilobajtov ali manj.',
        'string' => 'Polje mora vsebovati :value znakov ali manj.',
        'array' => 'Polje mora vsebovati :value vrednosti ali manj.',
    ],
    'max' => [
        'numeric' => 'Vrednost ne sme biti večja od :max.',
        'file' => 'Datoteka ne sme biti večja od :max kilobajtov.',
        'string' => 'Polje ne sme imeti več kot :max znakov.',
        'array' => 'Polje ne sme vsebovati več kot :max vrednosti.',
    ],
    'mimes' => 'Polje mora vsebovati datoteko z enim od naslednjih formatov: :values.',
    'mimetypes' => 'Polje mora vsebovati datoteko z enim od naslednjih tipov: :values.',
    'min' => [
        'numeric' => 'Vrednost mora biti vsaj :min.',
        'file' => 'Datoteka mora biti velika vsaj :min kilobajtov.',
        'string' => 'Polje mora vsebovati vsaj :min znakov.',
        'array' => 'Polje mora vsebovati vsaj :min vrednosti.',
    ],
    'not_in' => 'Izbrana vrednost ni veljavna.',
    'not_regex' => 'Vrednost nima pravilnega formata.',
    'numeric' => 'Vrednost mora biti številka.',
    'password' => 'Geslo je nepravilno.',
    'present' => 'Polje :attribute mora biti prisotno.',
    'regex' => 'Vrednost nima pravilnega formata.',
    'required' => 'Polje je obvezno.',
    'required_if' => 'Polje je obvezno kadar je vrednost polja :other enaka :value.',
    'required_unless' => 'Polje je obvezno razen če je vrednost polja :other enaka eni od naslednjih: :values.',
    'required_with' => 'Polje je obvezno kadar je prisotna ena od vrednosti: :values.',
    'required_with_all' => 'Polje je obvezno kadar so prisotne vrednosti: :values.',
    'required_without' => 'Polje je obvezno kadar ni prisotna ena od vrednosti: :values.',
    'required_without_all' => 'Polje je obvezno kadar ni prisotna ena od vrednosti: :values.',
    'same' => 'Polji :attribute in :other se morata ujemati.',
    'size' => [
        'numeric' => 'Vrednost mora biti :size.',
        'file' => 'Datoteka mora biti velika :size kilobajtov.',
        'string' => 'Polje mora vsebovati :size znakov.',
        'array' => 'Polje mora vsebovati :size vrednosti.',
    ],
    'starts_with' => 'Vrednost se mora začeti z eno od naslednjih: :values.',
    'string' => 'Vrednost mora biti beseda.',
    'timezone' => 'Vrednost mora biti veljaven časovni pas.',
    'unique' => 'Vrednost mora biti unikatna.',
    'uploaded' => 'Vrednosti polja ni bilo možno naložiti.',
    'url' => 'Vrednost nima pravilnega formata.',
    'uuid' => 'Vrednost mora biti veljaven UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'personData' => [
            'email' => [
                'unique' => 'Ta email naslov je že v uporabi.',
            ],
            'date_of_birth' => [
                'before' => 'Datum rojstva mora biti v preteklosti.',
            ],
            'exists' => 'Uporabnik s to šifro ne obstaja v bazi podatkov.'
        ],
        'sponsor' => [
            'email' => [
                'unique' => 'Ta email naslov je že v uporabi.',
            ],
            'date_of_birth' => [
                'before' => 'Datum rojstva mora biti v preteklosti.',
            ],
            'exists' => 'Uporabnik s to šifro ne obstaja v bazi podatkov.'
        ],
        'name' => [
            'min' => 'Ime mora biti dolgo vsaj 2 znaka.',
        ],
        'email' => [
            'unique' => 'Ta email naslov je že v uporabi.',
        ],
        'password' => [
            'min' => 'Geslo mora biti dolgo vsaj :min znakov.',
            'confirmed' => 'Gesli se ne ujemata.',
        ],
        'date_of_birth' => [
            'before' => 'Datum rojstva mora biti v preteklosti.',
        ],
        'date_of_arrival_mh' => [
            'before' => 'Datum sprejema v zavetišče mora biti v preteklosti.',
            'after_or_equal' => 'Datum sprejema v zavetišče mora biti kasnejši ali enak datumu rojstva.',
        ],
        'date_of_arrival_boter' => [
            'before' => 'Datum vstopa v botrstvo mora biti v preteklosti.',
            'after_or_equal' => 'Datum vstopa v botrstvo mora biti kasnejši ali enak datumu rojstva.',
        ],
        'monthly_amount' => [
            'min' => 'Minimalni mesečni znesek je 5€.',
        ],
        'cat' => [
            'exists' => 'Muca s to šifro ne obstaja v bazi podatkov.',
        ],
        'ended_at' => [
            'before_or_equal' => 'Datum konca ne sme biti v prihodnosti.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
