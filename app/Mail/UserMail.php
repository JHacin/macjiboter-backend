<?php

namespace App\Mail;

use App\Mail\Client\MailClient;
use App\Models\User;

class UserMail
{

    private MailClient $mailClient;

    public function __construct(MailClient $mailClient)
    {
        $this->mailClient = $mailClient;
    }

    /**
     * @param \App\Models\User $user
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     */
    public function sendWelcomeEmail(User $user)
    {
        $this->mailClient->send([
            'to' => $user->email,
            'subject' => 'Dobrodošli na strani Mačji boter',
            'template' => 'user_welcome',
        ]);
    }
}
