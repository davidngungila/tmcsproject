<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'snippe' => [
        'api_key' => env('SNIPPE_API_KEY'),
        'webhook_secret' => env('SNIPPE_WEBHOOK_SECRET'),
        'base_url' => rtrim(env('SNIPPE_API_BASE_URL', 'https://api.snippe.sh'), '/'),
        'post_payment_redirect_url' => env('SNIPPE_POST_PAYMENT_REDIRECT_URL', 'https://tmcs.feedtancmg.org/member/profile'),
        'webhook_url' => env('SNIPPE_WEBHOOK_URL'),
    ],

    'messaging' => [
        'token' => env('MESSAGING_API_TOKEN'),
        'sender_id' => env('MESSAGING_SENDER_ID', 'TMCS MoCU'),
        'base_url' => env('MESSAGING_BASE_URL', 'https://messaging-service.co.tz'),
    ],

];
