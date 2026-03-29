<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\DeviceAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TelegramLinkingTest extends TestCase
{
    use RefreshDatabase;

    public function test_onboarding_normalizes_phone_before_saving(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withUnencryptedCookie(DeviceAccessService::COOKIE_NAME, str_repeat('1', 40))
            ->postJson(route('student.onboarding.store'), [
                'name' => 'Test Student',
                'age' => 22,
                'address' => 'Cairo',
                'secondary_email' => 'backup@example.com',
                'phone' => '01012345678',
            ]);

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertSame('+201012345678', $user->fresh()->phone);
    }

    public function test_telegram_start_and_phone_linking_messages_use_arabic_and_country_code_examples(): void
    {
        config(['services.telegram.bot_token' => 'test-token']);

        Http::fake([
            'https://api.telegram.org/*' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 101],
            ], 200),
        ]);

        $user = User::factory()->create([
            'phone' => '+201012345678',
            'telegram_chat_id' => null,
            'telegram_linked_at' => null,
        ]);

        $this->postJson(route('telegram.webhook'), [
            'message' => [
                'chat' => ['id' => 555001],
                'text' => '/start',
            ],
        ])->assertOk();

        Http::assertSent(function ($request) {
            $text = (string) $request['text'];

            return str_contains($text, 'كود الدولة')
                && str_contains($text, '+9665XXXXXXXX')
                && str_contains($text, '+2010XXXXXXX');
        });

        Http::fake([
            'https://api.telegram.org/*' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 102],
            ], 200),
        ]);

        $this->postJson(route('telegram.webhook'), [
            'message' => [
                'chat' => ['id' => 555001],
                'text' => '+201012345678',
            ],
        ])->assertOk();

        $user->refresh();

        $this->assertSame('555001', (string) $user->telegram_chat_id);
        $this->assertNotNull($user->telegram_linked_at);

        Http::assertSent(function ($request) {
            return str_contains((string) $request['text'], 'تم ربط حسابك بنجاح');
        });
    }
}
