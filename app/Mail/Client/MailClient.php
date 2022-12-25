<?php

namespace App\Mail\Client;

use App\Settings\Settings;
use Exception;
use Log;
use Mailgun\Mailgun;

class MailClient
{
    private Mailgun $client;
    private string $domain;

    public function __construct(Mailgun $client)
    {
        $this->client = $client;
        $this->domain = config('services.mailgun.domain');
    }

    public function send(array $params)
    {
        if (!Settings::hasValueTrue(Settings::KEY_ENABLE_EMAILS)) {
            return;
        }

        if (config('app.env') !== 'production') {
            $params['to'] = config('mail.vars.test_to_address');
        }

        $this->client->messages()->send(
            $this->domain,
            array_merge(['from' => config('mail.from.address')], $params)
        );
    }

    public function addMemberToList(string $list, string $email, array $variables)
    {
        if (!Settings::hasValueTrue(Settings::KEY_ENABLE_MAILING_LISTS)) {
            return;
        }

        try {
            $this->client->mailingList()->member()->create(
                $this->constructListAddress($list),
                $email,
                null,
                $variables
            );
        } catch (Exception $e) {
            $this->logException($e);
        }
    }

    public function updateListMember(string $list, string $email, array $parameters)
    {
        if (!Settings::hasValueTrue(Settings::KEY_ENABLE_MAILING_LISTS)) {
            return;
        }

        try {
            $this->client->mailingList()->member()->update(
                $this->constructListAddress($list),
                $email,
                $parameters
            );
        } catch (Exception $e) {
            $this->logException($e);
        }
    }

    public function removeMemberFromList(string $list, string $email)
    {
        if (!Settings::hasValueTrue(Settings::KEY_ENABLE_MAILING_LISTS)) {
            return;
        }

        try {
            $this->client->mailingList()->member()->delete(
                $this->constructListAddress($list),
                $email
            );
        } catch (Exception $e) {
            $this->logException($e);
        }
    }

    protected function constructListAddress(string $list): string
    {
        return sprintf('%s@%s', $list, config('services.mailgun.domain'));
    }

    protected function logException(Exception $e)
    {
        Log::error($e->getMessage(), ['trace' => $e->getTrace()]);
    }
}
