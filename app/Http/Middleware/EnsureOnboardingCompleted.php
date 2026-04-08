<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingCompleted
{
    /**
     * Routes that should be accessible even if onboarding is not completed.
     */
    protected array $except = [
        'student.onboarding',
        'student.onboarding.store',
        'student.onboarding.complete',
        'student.onboarding.check-telegram',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->onboarding_completed && !$this->isExcluded($request)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Please complete your profile setup first.',
                    'redirect' => route('student.onboarding'),
                ], 403);
            }

            return redirect()->route('student.onboarding');
        }

        return $next($request);
    }

    protected function isExcluded(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if (!$routeName) {
            return false;
        }

        return in_array($routeName, $this->except);
    }
}
