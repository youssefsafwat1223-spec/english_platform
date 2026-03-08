<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelegramController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Webhook\StreamPayWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Telegram Bot Webhook
Route::post('/telegram/webhook', [TelegramController::class, 'webhook'])
    ->name('telegram.webhook');

// StreamPay Payment Callback & Webhook
Route::get('/payment/callback/{payment}', [PaymentController::class, 'callback'])
    ->name('payment.callback');

Route::post('/payment/webhook', [StreamPayWebhookController::class, 'handle'])
    ->name('payment.webhook');