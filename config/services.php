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

    /*
    |----------------------------------------------------------------------
    | Writing AI (LanguageTool + Ollama)
    |----------------------------------------------------------------------
    */
    'writing_ai' => [
        'enabled' => env('WRITING_AI_ENABLED', false),
        'languagetool_url' => env('LANGUAGETOOL_URL', 'http://127.0.0.1:8010'),
        'ollama_url' => env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'),
        'ollama_model' => env('OLLAMA_MODEL', 'qwen2.5:7b'),
        'timeout_seconds' => (int) env('WRITING_AI_TIMEOUT_SECONDS', 45),
    ],

    /*
    |----------------------------------------------------------------------
    | Speaking AI (Ollama)
    |----------------------------------------------------------------------
    */
    'speaking_ai' => [
        'enabled' => env('SPEAKING_AI_ENABLED', false),
        'ollama_url' => env('SPEAKING_AI_OLLAMA_URL', env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434')),
        'ollama_model' => env('SPEAKING_AI_OLLAMA_MODEL', env('OLLAMA_MODEL', 'qwen2.5:7b')),
        'timeout_seconds' => (int) env('SPEAKING_AI_TIMEOUT_SECONDS', 30),
        'score_blend_ratio' => (float) env('SPEAKING_AI_SCORE_BLEND_RATIO', 0.35),
    ],

];
