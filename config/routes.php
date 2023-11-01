<?php

return [
//    'home' => '/',
//    'login' => '/prijava',
//    'logout' => '/odjava',
//    'register' => '/registracija',
//    'forgot_password' => '/geslo/ponastavitev',
//    'reset_password_form' => '/geslo/ponastavitev/{token}',
//    'send_reset_link_email' => '/geslo/email',
//    'user_profile' => '/profil',
//    'become_sponsor_overview' => '/postani-boter',
//    'why_become_sponsor' => '/zakaj-postati-boter',
//    'cat_list' => '/muce',
//    'cat_details' => '/muce/{cat}',
//    'cat_sponsorship_form' => '/muce/{cat}/postani-boter',
//    'special_sponsorships' => '/posebna-botrstva',
//    'special_sponsorships_form' => '/posebna-botrstva/obrazec',
//    'special_sponsorships_archive' => '/posebna-botrstva/arhiv',
//    'gift_sponsorship' => '/podari-botrstvo',
//    'news' => '/novice',
//    'faq' => '/pogosta-vprasanja',
//    'privacy' => '/zasebnost',
    'admin' => [
        'dashboard' => 'dashboard',
        'login' => 'login',
        'users' => 'uporabniki',
        'users_add' => 'uporabniki/create',
        'users_edit' => 'uporabniki/{id}/edit',
        'roles' => 'vloge',
        'permissions' => 'dovoljenja',
        'cats' => 'muce',
        'cats_add' => 'muce/create',
        'cats_edit' => 'muce/{id}/edit',
        'cat_locations' => 'lokacije',
        'cat_locations_add' => 'lokacije/create',
        'cat_locations_edit' => 'lokacije/{id}/edit',
        'sponsorships' => 'botrstva',
        'sponsorships_add' => 'botrstva/create',
        'sponsorships_edit' => 'botrstva/{id}/edit',
        'sponsorships_cancel' => 'botrstva/{sponsorship}/cancel',
        'special_sponsorships' => 'posebna-botrstva',
        'special_sponsorships_add' => 'posebna-botrstva/create',
        'special_sponsorships_edit' => 'posebna-botrstva/{id}/edit',
        'sponsors' => 'botri',
        'sponsors_add' => 'botri/create',
        'sponsors_edit' => 'botri/{id}/edit',
        'sponsor_cancel_all_sponsorships' => 'botri/{sponsor}/cancel-all-sponsorships',
        'sponsorship_message_types' => 'vrste-pisem',
        'sponsorship_message_types_add' => 'vrste-pisem/create',
        'sponsorship_message_types_edit' => 'vrste-pisem/{id}/edit',
        'sponsorship_messages' => 'pisma',
        'sponsorship_messages_add' => 'pisma/create',
        'notify_active_sponsors' => 'pisma-aktivnim-botrom',
        'get_active_sponsorships_for_cat' => 'pisma/{cat:id}/get-active-sponsorships',
        'get_messages_sent_to_sponsor' => 'pisma/{sponsor}/get-sent-messages',
        'get_parsed_template_preview' => 'pisma/parsed-template-preview',
        'news' => 'novice',
    ]
];
