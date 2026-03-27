<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Symfony\Component\HttpFoundation\Response;

class RequireAdminTwoFactor
{
    public const SESSION_VERIFIED = 'admin_two_factor_passed';
    public const SESSION_USER_ID = 'admin_two_factor_user_id';

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_admin || !$user->hasTwoFactorEnabled()) {
            return $next($request);
        }

        if (self::isVerified($request, $user)) {
            return $next($request);
        }

        if (!$request->routeIs('admin.two-factor.*')) {
            $request->session()->put('url.intended', $request->fullUrl());
        }

        return redirect()->route('admin.two-factor.challenge');
    }

    public static function markVerified(Store $session, User $user): void
    {
        $session->put([
            self::SESSION_VERIFIED => true,
            self::SESSION_USER_ID => $user->getAuthIdentifier(),
        ]);
    }

    public static function forgetVerification(Store $session): void
    {
        $session->forget([
            self::SESSION_VERIFIED,
            self::SESSION_USER_ID,
        ]);
    }

    public static function isVerified(Request $request, User $user): bool
    {
        return filter_var($request->session()->get(self::SESSION_VERIFIED), FILTER_VALIDATE_BOOL)
            && (int) $request->session()->get(self::SESSION_USER_ID) === (int) $user->getAuthIdentifier();
    }
}
