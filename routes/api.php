<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelegramController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Telegram Bot Webhook
Route::post('/telegram/webhook', [TelegramController::class, 'webhook'])
    ->name('telegram.webhook');

// Tap Payment Callback & Webhook
Route::get('/payment/callback/{payment}', [PaymentController::class, 'callback'])
    ->name('payment.callback');

Route::post('/payment/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook');