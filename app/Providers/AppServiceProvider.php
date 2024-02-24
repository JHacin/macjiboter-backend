<?php

namespace App\Providers;

use App\Mail\Client\MailClient;
use App\Mail\Client\TemplateApiClient;
use App\Mail\MailTemplateParser;
use App\Mail\SpecialSponsorshipMail;
use App\Mail\SponsorshipMail;
use App\Mail\UserMail;
use Handlebars\Handlebars;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mailgun\Mailgun;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MailClient::class, function () {
            return new MailClient(
                Mailgun::create(
                    config('services.mailgun.secret'),
                    config('services.mailgun.scheme').'://'.config('services.mailgun.endpoint')
                )
            );
        });
        $this->app->singleton(MailTemplateParser::class, function () {
            return new MailTemplateParser(new Handlebars());
        });
        $this->app->singleton(TemplateApiClient::class, function () {
            return new TemplateApiClient();
        });
        $this->app->singleton(UserMail::class, function (Application $app) {
            return new UserMail($app->make(MailClient::class));
        });
        $this->app->singleton(SponsorshipMail::class, function (Application $app) {
            return new SponsorshipMail($app->make(MailClient::class));
        });
        $this->app->singleton(SpecialSponsorshipMail::class, function (Application $app) {
            return new SpecialSponsorshipMail($app->make(MailClient::class));
        });
    }

    public function boot(): void
    {
        //
    }
}
