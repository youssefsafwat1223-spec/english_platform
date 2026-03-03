<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\TelegramBotSetting;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function index()
    {
        return view('admin.settings.index');
    }

    public function general()
    {
        $settings = SystemSetting::getByGroup('general');

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_url' => 'required|url',
            'contact_email' => 'required|email',
            'timezone' => 'required|string',
        ]);

        SystemSetting::set('site_name', $request->site_name, 'string', 'general', true);
        SystemSetting::set('site_url', $request->site_url, 'string', 'general', true);
        SystemSetting::set('contact_email', $request->contact_email, 'string', 'general');
        SystemSetting::set('timezone', $request->timezone, 'string', 'general');

        return back()->with('success', 'General settings updated successfully!');
    }

    public function telegram()
    {
        $botInfo = $this->telegramService->getBotInfo();
        
        $settings = [
            'bot_token' => config('services.telegram.bot_token'),
            'webhook_url' => config('services.telegram.webhook_url'),
            'send_alternate_days' => TelegramBotSetting::get('send_alternate_days', true),
            'question_time' => TelegramBotSetting::get('question_time', '09:00'),
            'enable_notifications' => TelegramBotSetting::get('enable_notifications', true),
        ];

        return view('admin.settings.telegram', compact('botInfo', 'settings'));
    }

    public function updateTelegram(Request $request)
    {
        $request->validate([
            'send_alternate_days' => 'sometimes|boolean',
            'question_time' => 'required|date_format:H:i',
            'enable_notifications' => 'sometimes|boolean',
        ]);

        TelegramBotSetting::set('send_alternate_days', $request->boolean('send_alternate_days'), 'boolean');
        TelegramBotSetting::set('question_time', $request->question_time, 'string');
        TelegramBotSetting::set('enable_notifications', $request->boolean('enable_notifications'), 'boolean');

        return back()->with('success', 'Telegram settings updated successfully!');
    }

    public function setWebhook()
    {
        $url = config('services.telegram.webhook_url');
        
        $success = $this->telegramService->setWebhook($url);

        if ($success) {
            return back()->with('success', 'Webhook set successfully!');
        }

        return back()->with('error', 'Failed to set webhook.');
    }

    public function deleteWebhook()
    {
        $success = $this->telegramService->deleteWebhook();

        if ($success) {
            return back()->with('success', 'Webhook deleted successfully!');
        }

        return back()->with('error', 'Failed to delete webhook.');
    }

    public function payment()
    {
        $settings = [
            'tap_secret_key' => config('services.tap.secret_key'),
            'tap_public_key' => config('services.tap.public_key'),
            'currency' => SystemSetting::get('payment_currency', 'USD'),
            'tax_rate' => SystemSetting::get('tax_rate', 0),
        ];

        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|size:3',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        SystemSetting::set('payment_currency', $request->currency, 'string', 'payment');
        SystemSetting::set('tax_rate', $request->tax_rate, 'decimal', 'payment');

        return back()->with('success', 'Payment settings updated successfully!');
    }

    public function points()
    {
        $settings = [
            'points_per_lesson' => SystemSetting::get('points_per_lesson', config('app.points_per_lesson', 10)),
            'points_per_quiz' => SystemSetting::get('points_per_quiz', config('app.points_per_quiz', 30)),
            'points_per_daily_question' => SystemSetting::get('points_per_daily_question', config('app.points_per_daily_question', 5)),
            'points_per_pronunciation' => SystemSetting::get('points_per_pronunciation', config('app.points_per_pronunciation', 10)),
            'referral_discount_percentage' => SystemSetting::get('referral_discount_percentage', config('app.referral_discount_percentage', 10)),
        ];

        return view('admin.settings.points', compact('settings'));
    }

    public function updatePoints(Request $request)
    {
        $request->validate([
            'points_per_lesson' => 'required|integer|min:1',
            'points_per_quiz' => 'required|integer|min:1',
            'points_per_daily_question' => 'required|integer|min:1',
            'points_per_pronunciation' => 'required|integer|min:1',
            'referral_discount_percentage' => 'required|integer|min:0|max:100',
        ]);

        SystemSetting::set('points_per_lesson', $request->points_per_lesson, 'integer', 'gamification');
        SystemSetting::set('points_per_quiz', $request->points_per_quiz, 'integer', 'gamification');
        SystemSetting::set('points_per_daily_question', $request->points_per_daily_question, 'integer', 'gamification');
        SystemSetting::set('points_per_pronunciation', $request->points_per_pronunciation, 'integer', 'gamification');
        SystemSetting::set('referral_discount_percentage', $request->referral_discount_percentage, 'integer', 'referral');

        return back()->with('success', 'Points settings updated successfully!');
    }

    public function battle()
    {
        $settings = [
            'battle_lobby_timer'     => (int) SystemSetting::get('battle_lobby_timer', 120),
            'battle_question_timer'  => (int) SystemSetting::get('battle_question_timer', 30),
            'battle_min_questions'   => (int) SystemSetting::get('battle_min_questions', 5),
            'battle_max_questions'   => (int) SystemSetting::get('battle_max_questions', 15),
            'battle_min_players'     => (int) SystemSetting::get('battle_min_players', 2),
            'battle_max_players'     => (int) SystemSetting::get('battle_max_players', 10),
        ];

        return view('admin.settings.battle', compact('settings'));
    }

    public function updateBattle(Request $request)
    {
        $request->validate([
            'battle_lobby_timer'    => 'required|integer|min:30|max:600',
            'battle_question_timer' => 'required|integer|min:10|max:120',
            'battle_min_questions'  => 'required|integer|min:1|max:50',
            'battle_max_questions'  => 'required|integer|min:1|max:50',
            'battle_min_players'    => 'required|integer|min:2|max:10',
            'battle_max_players'    => 'required|integer|min:2|max:10',
        ]);

        SystemSetting::set('battle_lobby_timer',    $request->battle_lobby_timer,    'integer', 'battle');
        SystemSetting::set('battle_question_timer', $request->battle_question_timer, 'integer', 'battle');
        SystemSetting::set('battle_min_questions',  $request->battle_min_questions,  'integer', 'battle');
        SystemSetting::set('battle_max_questions',  $request->battle_max_questions,  'integer', 'battle');
        SystemSetting::set('battle_min_players',    $request->battle_min_players,    'integer', 'battle');
        SystemSetting::set('battle_max_players',    $request->battle_max_players,    'integer', 'battle');

        return back()->with('success', '⚔️ Battle settings updated successfully!');
    }
}

