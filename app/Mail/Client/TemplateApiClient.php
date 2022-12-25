<?php

namespace App\Mail\Client;

use Http;

class TemplateApiClient
{
    public function retrieveTemplate(string $templateId): string
    {
        $requestUrl =
            config('services.mailgun.endpoint') .
            '/v3/' .
            config('services.mailgun.domain') .
            '/templates/' .
            $templateId;

        $client = Http::withBasicAuth('api', config('services.mailgun.secret'));
        $response = $client->get($requestUrl, ['active' => 'yes']);

        if (!$response->successful()) {
            $status = $response->status();
            $message = $status === 404
                ? 'Predloga s to šifro ne obstaja.'
                : 'Prišlo je do napake pri pridobivanju podatkov o predlogi sporočila.';

            abort($status, $message);
        }

        return $response->json()['template']['version']['template'];
    }
}
