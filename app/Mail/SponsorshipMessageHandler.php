<?php

namespace App\Mail;

use App\Mail\Client\MailClient;
use App\Models\Cat;
use App\Models\PersonData;
use App\Models\SponsorshipMessage;

class SponsorshipMessageHandler
{
    private MailClient $mailClient;

    public function __construct(MailClient $mailClient)
    {
        $this->mailClient = $mailClient;
    }


    /**
     * @param \App\Models\SponsorshipMessage $message
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     */
    public function send(SponsorshipMessage $message)
    {
        $this->mailClient->send([
            'to' => $message->sponsor->email,
            'bcc' => config('mail.vars.bcc_copy_address'),
            'subject' => $message->messageType->subject,
            'template' => $message->messageType->template_id,
            'h:X-Mailgun-Variables' => json_encode([
                'ime_botra' => $message->sponsor->first_name,
                'boter_moski' => $message->sponsor->gender === PersonData::GENDER_MALE,
                'ime_muce' => $message->cat->name,
                'muca_moski' => $message->cat->gender === Cat::GENDER_MALE,
            ]),
        ]);
    }
}
