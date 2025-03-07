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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'writer' => [
        'api' => [
            'token' => env('WRITER_API_TOKEN'),
            'url' => env('WRITER_API_URL'),
        ],
        'cryptKey' => env('WRITER_CRYPT_KEY'),
    ],

    'google' => [
        'search' => [
            'key' => env('GOOGLE_SEARCH_API_KEY'),
            'id' => env('GOOGLE_SEARCH_ID')
        ],
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_URL'),
        'recaptcha' => [
            'key' => env('GOOGLE_RECAPTCHA_KEY', null),
            'secret' => env('GOOGLE_RECAPTCHA_SECRET', null),
        ],
    ],

    'ongage' => [
        'username' => env('ONGAGE_USERNAME'),
        'password' => env('ONGAGE_PASSWORD'),
        'account_code' => env('ONGAGE_ACCOUNT_CODE'),
        'list_id' => env('ONGAGE_LIST_ID'),
        'esp_connection_id' => env('ONGAGE_ESP_CONNECTION_ID'),
    ],

    'neverbounce' => [
        'api_key' => env('NEVERBOUNCE_API_KEY'),
        'valid_results' => [
            'valid'
        ],
    ],

    'beehiiv' => [
        'api_key' => env('BEEHIIV_API_KEY'),
        'api_version' => env('BEEHIIV_API_VERSION', 2),
        'publication_id' => env('BEEHIIV_PUBLICATION_ID'),
    ],

    'data_reporting' => [
        'api_url' => env('DR_API_URL', null),
        'api_key' => env('DR_API_KEY', null),
    ],

    'commento' => [
        'sso_secret' => env('COMMENTO_SSO_SECRET'),
    ],

];
