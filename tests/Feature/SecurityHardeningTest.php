<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_sets_security_headers(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Content-Security-Policy');

        $contentSecurityPolicy = (string) $response->headers->get('Content-Security-Policy');

        $this->assertStringContainsString("default-src 'self'", $contentSecurityPolicy);
        $this->assertStringContainsString("object-src 'none'", $contentSecurityPolicy);
        $this->assertStringContainsString('script-src-elem', $contentSecurityPolicy);
        $response->assertSee('nonce=', false);
    }

    public function test_forgot_password_uses_generic_response_for_unknown_email(): void
    {
        Notification::fake();

        User::factory()->create([
            'email' => 'student@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'missing@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'If the email address exists in our system, a reset link has been sent.');
        $response->assertSessionDoesntHaveErrors('email');
    }

    public function test_contact_form_is_rate_limited_with_friendly_error(): void
    {
        Mail::fake();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('contact.send'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'subject' => 'Hello',
                'message' => 'This is a valid test message.',
            ])->assertRedirect();
        }

        $response = $this->post(route('contact.send'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => 'Hello',
            'message' => 'This is a valid test message.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('rate_limit');
    }
}
