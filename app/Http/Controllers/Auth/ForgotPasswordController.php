<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => __('Email address is required.'),
            'email.email' => __('Please enter a valid email address.'),
        ]);

        $key = $this->throttleKey($request, $request->input('email', ''));

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors([
                'email' => __('Please try again in :minutes minute(s).', ['minutes' => (int) ceil($seconds / 60)]),
            ]);
        }

        RateLimiter::hit($key, 600);

        $status = Password::sendResetLink($request->only('email'));

        if (in_array($status, [Password::RESET_LINK_SENT, Password::INVALID_USER], true)) {
            return back()->with('status', __('If the email address exists in our system, a reset link has been sent.'));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    protected function throttleKey(Request $request, string $email): string
    {
        return 'password-reset:' . Str::lower(trim($email)) . '|' . $request->ip();
    }
}
