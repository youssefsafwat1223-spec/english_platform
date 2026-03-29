<?php

namespace App\Http\Middleware;

use App\Services\DeviceAccessService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedDevice
{
    public function __construct(private readonly DeviceAccessService $deviceAccessService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_student) {
            return $next($request);
        }

        $result = $this->deviceAccessService->authorizeDevice($user, $request);

        if ($result['allowed']) {
            return $next($request);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => 'هذا الجهاز غير معتمد لهذا الحساب. تم إرسال طلب استبدال الجهاز إلى الإدارة للمراجعة.',
        ]);
    }
}
