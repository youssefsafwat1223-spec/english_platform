<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    */

    'timezone' => 'Africa/Cairo',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Gamification Points
    |--------------------------------------------------------------------------
    */

    'points_per_lesson' => env('POINTS_PER_LESSON', 10),
    'points_per_quiz' => env('POINTS_PER_QUIZ', 30),
    'points_per_daily_question' => env('POINTS_PER_DAILY_QUESTION', 5),
    'points_per_pronunciation' => env('POINTS_PER_PRONUNCIATION', 10),

    /*
    |--------------------------------------------------------------------------
    | Referral System
    |--------------------------------------------------------------------------
    */

    'referral_discount_percentage' => env('REFERRAL_DISCOUNT_PERCENTAGE', 10),

    /*
    |--------------------------------------------------------------------------
    | Daily Questions
    |--------------------------------------------------------------------------
    */

    'send_daily_questions_alternate_days' => env('SEND_DAILY_QUESTIONS_ALTERNATE_DAYS', true),
    'daily_question_time' => env('DAILY_QUESTION_TIME', '18:00'),

];