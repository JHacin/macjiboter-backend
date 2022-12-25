<?php

namespace App\Mail;

use App\Mail\Client\MailClient;
use App\Models\PersonData;

class SponsorMailingListManager
{
    public const ALL_SPONSORS_LIST_ADDRESS = 'vsi_botri';

    protected MailClient $mailClient;

    public function __construct(MailClient $mailClient)
    {
        $this->mailClient = $mailClient;
    }

    public function addToAllLists(PersonData $sponsor)
    {
        $this->mailClient->addMemberToList(
            self::ALL_SPONSORS_LIST_ADDRESS,
            $sponsor->email,
            $this->constructVariables($sponsor),
        );
    }

    public function updateProperties(PersonData $sponsor, string $previousEmail)
    {
        $parameters = [
            'vars' => $this->constructVariables($sponsor),
            'address' => $sponsor->email,
        ];

        $this->mailClient->updateListMember(
            self::ALL_SPONSORS_LIST_ADDRESS,
            $previousEmail,
            $parameters,
        );
    }

    public function removeFromAllLists(PersonData $sponsor)
    {
        $this->mailClient->removeMemberFromList(self::ALL_SPONSORS_LIST_ADDRESS, $sponsor->email);
    }

    // Todo: find a better way to ensure that $sponsor->gender is received as an integer.
    private function constructVariables(PersonData $sponsor): array
    {
        return [
            'boter_moski' => $sponsor->gender == PersonData::GENDER_MALE,
            'boter_ime' => $sponsor->first_name,
            'boter_priimek' => $sponsor->last_name,
        ];
    }
}
