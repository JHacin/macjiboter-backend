<?php

namespace App\Mail;

use App\Mail\Client\MailClient;
use App\Models\PersonData;
use App\Models\SpecialSponsorship;
use App\Utilities\CountryList;
use App\Utilities\CurrencyFormat;

class SpecialSponsorshipMail
{
    private MailClient $mailClient;

    public function __construct(MailClient $mailClient)
    {
        $this->mailClient = $mailClient;
    }

    public function sendInitialInstructionsEmail(SpecialSponsorship $sponsorship)
    {

        $sponsor = $sponsorship->sponsor;
        $payer = $sponsorship->payer ?? $sponsor;

        $variables = [
            'app_url' => config('app.url'),
            'boter_moski' => $sponsor->gender === PersonData::GENDER_MALE,
            'boter_ime' => $sponsor->first_name ?? '/',
            'boter_priimek' => $sponsor->last_name ?? '/',
            'boter_naslov' => $sponsor->address ?? '/',
            'boter_postna_stevilka' => $sponsor->zip_code ?? '/',
            'boter_kraj' => $sponsor->city ?? '/',
            'boter_drzava' => $sponsor->country ? CountryList::COUNTRY_NAMES[$sponsor->country] : '/',
            'boter_email' => $sponsor->email,
            'placnik_ime' => $payer->first_name ?? '/',
            'placnik_priimek' => $payer->last_name ?? '/',
            'placnik_naslov' => $payer->address ?? '/',
            'placnik_postna_stevilka' => $payer->zip_code ?? '/',
            'placnik_kraj' => $payer->city ?? '/',
            'placnik_drzava' => $payer->country ? CountryList::COUNTRY_NAMES[$payer->country] : '/',
            'placnik_email' => $payer->email,
            'vrsta_botrstva' => SpecialSponsorship::TYPE_LABELS[$sponsorship->type],
            'je_darilo' => $sponsorship->is_gift === true,
            'je_anonimno' => $sponsorship->is_anonymous === true,
            'znesek' => CurrencyFormat::format($sponsorship->amount),
            'namen_nakazila' => $sponsorship->payment_purpose,
            'referencna_stevilka' => $sponsorship->payment_reference_number,
        ];

        $params = [
            'to' => $payer->email,
            'bcc' => config('mail.vars.bcc_copy_address'),
            'subject' => 'Navodila po izpolnitvi obrazca za botrstvo',
            'template' => 'navodila_za_posebno_botrstvo',
            'h:X-Mailgun-Variables' => json_encode($variables)
        ];

        $this->mailClient->send($params);
    }
}
