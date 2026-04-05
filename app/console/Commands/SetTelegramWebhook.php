<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook';
    protected $description = 'Set the Telegram bot webhook URL based on the current APP_URL or TELEGRAM_WEBHOOK_URL';

    public function handle()
    {
        $token = config('services.telegram.bot_token');

        if (!$token) {
            $this->error('TELEGRAM_BOT_TOKEN is not set in your .env file.');
            return 1;
        }

        $webhookUrl = config('services.telegram.webhook_url');

        if (!$webhookUrl) {
            $appUrl = config('app.url');
            $webhookUrl = rtrim($appUrl, '/') . '/api/telegram/webhook';
        }

        $this->info("Setting webhook to: {$webhookUrl}");

        $response = Http::post("https://api.telegram.org/bot{$token}/setWebhook", [
            'url' => $webhookUrl,
        ]);

        if ($response->successful()) {
            $this->info('Webhook set successfully!');
            $this->info($response->body());
            return 0;
        }

        $this->error('Failed to set webhook.');
        $this->error($response->body());
        return 1;
    }
}

