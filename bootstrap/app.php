<?php

use App\Services\DeviceAccessService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: [DeviceAccessService::COOKIE_NAME]);
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'admin.2fa' => \App\Http\Middleware\RequireAdminTwoFactor::class,
            'student' => \App\Http\Middleware\IsStudent::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
            'approved.device' => \App\Http\Middleware\EnsureApprovedDevice::class,
            'track.activity' => \App\Http\Middleware\TrackUserActivity::class,
            'enrolled' => \App\Http\Middleware\CheckCourseEnrollment::class,
            'onboarding' => \App\Http\Middleware\EnsureOnboardingCompleted::class,
            'feature' => \App\Http\Middleware\EnsureFeatureEnabled::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
