<?php

namespace App\Http\Middleware;

use App\Services\PlatformFeatureService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    public function __construct(private readonly PlatformFeatureService $platformFeatureService)
    {
    }

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $enabled = match ($feature) {
            'live-sessions', 'live_sessions' => $this->platformFeatureService->liveSessionsEnabled(),
            default => true,
        };

        abort_unless($enabled, 404);

        return $next($request);
    }
}
