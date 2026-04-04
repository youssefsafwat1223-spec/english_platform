<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
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

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot
    |--------------------------------------------------------------------------
    */

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'bot_username' => env('TELEGRAM_BOT_USERNAME'),
        'webhook_url' => env('TELEGRAM_WEBHOOK_URL'),
        'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Tap Payment Gateway
    |--------------------------------------------------------------------------
    */

    'tap' => [
        'secret_key' => env('TAP_SECRET_KEY'),
        'public_key' => env('TAP_PUBLIC_KEY'),
        'webhook_url' => env('TAP_WEBHOOK_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | StreamPay Payment Gateway
    |--------------------------------------------------------------------------
    */

    'streampay' => [
        'api_key' => env('STREAMPAY_API_KEY'),
        'secret_key' => env('STREAMPAY_SECRET_KEY'),
        'webhook_url' => env('STREAMPAY_WEBHOOK_URL', env('APP_URL') . '/api/payment/webhook'),
        'api_url' => 'https://stream-app-service.streampay.sa/api/v2',
        'installments_enabled' => env('STREAMPAY_INSTALLMENTS_ENABLED', false),
        'webhook_tolerance' => env('STREAMPAY_WEBHOOK_TOLERANCE', 300),
        'consumer_enabled' => env('STREAMPAY_CONSUMER_ENABLED', true),
        'consumer_communication_methods' => env('STREAMPAY_CONSUMER_COMMUNICATION_METHODS', 'WHATSAPP,EMAIL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google OAuth
    |--------------------------------------------------------------------------
    */

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
        'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
        'credentials_path' => env('GOOGLE_APPLICATION_CREDENTIALS'),
    ],

    /*
    |----------------------------------------------------------------------
    | Realtime Pronunciation Streaming (Whisper Gateway)
    |----------------------------------------------------------------------
    */
    'pronunciation_stream' => [
        'enabled' => env('PRONUNCIATION_STREAM_ENABLED', false),
        'ws_url' => env('PRONUNCIATION_STREAM_WS_URL', 'ws://127.0.0.1:8787/ws'),
        'chunk_ms' => (int) env('PRONUNCIATION_STREAM_CHUNK_MS', 1000),
    ],

];
