<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function __construct(private readonly TelegramService $telegramService)
    {
    }

    public function show()
    {
        $user = auth()->user();

        // If already completed onboarding, redirect to dashboard
        if ($user->onboarding_completed) {
            return redirect()->route('student.dashboard');
        }

        return view('student.onboarding', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:5', 'max:120'],
            'address' => ['nullable', 'string', 'max:500'],
            'secondary_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . auth()->id()],
        ]);

        $normalizedPhone = $this->telegramService->normalizePhoneNumber($validated['phone']);

        if (!$normalizedPhone) {
            $message = __('ui.onboarding.invalid_phone_server');

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => ['phone' => [$message]],
                ], 422);
            }

            return back()->withErrors(['phone' => $message]);
        }

        $user = auth()->user();
        $user->update([
            'name' => $validated['name'],
            'age' => $validated['age'],
            'address' => $validated['address'] ?? null,
            'secondary_email' => $validated['secondary_email'] ?? null,
            'phone' => $normalizedPhone,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('profile_saved', true);
    }

    public function complete()
    {
        $user = auth()->user();
        $user->update(['onboarding_completed' => true]);

        return redirect()->route('student.dashboard')->with('success', __('Profile saved'));
    }

    public function checkTelegram()
    {
        $user = auth()->user();

        return response()->json([
            'linked' => !is_null($user->telegram_linked_at),
            'username' => $user->telegram_username,
        ]);
    }
}
