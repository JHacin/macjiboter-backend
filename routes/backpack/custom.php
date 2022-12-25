<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array)config('backpack.base.web_middleware', 'web'),
        (array)config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Admin\Controllers',
], function () {
    Route::crud(config('routes.admin.cats'), 'CatCrudController');
    Route::crud(config('routes.admin.sponsorships'), 'SponsorshipCrudController');
    Route::post(config('routes.admin.sponsorships_cancel'), 'SponsorshipCrudController@cancelSponsorship')->name('admin.sponsorship_cancel');
    Route::crud(config('routes.admin.cat_locations'), 'CatLocationCrudController');
    Route::crud(config('routes.admin.sponsors'), 'SponsorCrudController');
    Route::post(config('routes.admin.sponsor_cancel_all_sponsorships'), 'SponsorCrudController@cancelAllSponsorships')->name('admin.sponsor_cancel_all_sponsorships');
    Route::crud(config('routes.admin.sponsorship_message_types'), 'SponsorshipMessageTypeCrudController');
    Route::crud(config('routes.admin.sponsorship_messages'), 'SponsorshipMessageCrudController');
    Route::get(config('routes.admin.get_messages_sent_to_sponsor'), 'SponsorshipMessageCrudController@getMessagesSentToSponsor')->name('admin.get_messages_sent_to_sponsor');
    Route::get(config('routes.admin.get_parsed_template_preview'), 'SponsorshipMessageCrudController@getParsedTemplatePreview')->name('admin.get_parsed_template_preview');
    Route::crud(config('routes.admin.news'), 'NewsCrudController');
    Route::crud(config('routes.admin.special_sponsorships'), 'SpecialSponsorshipCrudController');
});

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
}); // this should be the absolute last line of this file
