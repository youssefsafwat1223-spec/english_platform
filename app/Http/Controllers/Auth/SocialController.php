<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Failed to connect with Google. Please try again.']);
        }

        // 1) Check if user already exists by google_id
        $user = User::where('google_id', $googleUser->id)->first();

        if ($user) {
            Auth::login($user);

            // If user hasn't completed onboarding, redirect to onboarding
            if (!$user->onboarding_completed) {
                return redirect()->route('student.onboarding');
            }

            return redirect()->intended(route('student.dashboard'));
        }

        // 2) Check if user exists by email
        $existingUser = User::where('email', $googleUser->email)->first();

        if ($existingUser) {
            // Link Google account to existing user
            $existingUser->update([
                'google_id' => $googleUser->id,
                'auth_type' => 'google',
            ]);
            Auth::login($existingUser);

            // If user hasn't completed onboarding, redirect to onboarding
            if (!$existingUser->onboarding_completed) {
                return redirect()->route('student.onboarding');
            }

            return redirect()->intended(route('student.dashboard'));
        }

        // 3) Brand new user — create and redirect to onboarding
        $newUser = User::create([
            'name' => $googleUser->name ?? 'Student',
            'email' => $googleUser->email,
            'password' => Hash::make(Str::random(24)),
            'google_id' => $googleUser->id,
            'auth_type' => 'google',
            'avatar' => $googleUser->avatar ?? null,
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
            'onboarding_completed' => false, // Explicitly false for new users
        ]);

        Auth::login($newUser);

        // Redirect to main onboarding flow
        return redirect()->route('student.onboarding');
    }
}
