<?php

namespace App\Providers;

use App\Services\PlatformFeatureService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $liveSessionsEnabled = true;
        $showCourseStudentCount = true;

        try {
            if (Schema::hasTable('system_settings')) {
                $platformFeatureService = app(PlatformFeatureService::class);
                $liveSessionsEnabled = $platformFeatureService->liveSessionsEnabled();
                $showCourseStudentCount = $platformFeatureService->courseStudentCountVisible();
            }
        } catch (\Throwable) {
            $liveSessionsEnabled = true;
            $showCourseStudentCount = true;
        }

        View::share('liveSessionsEnabled', $liveSessionsEnabled);
        View::share('showCourseStudentCount', $showCourseStudentCount);

        RateLimiter::for('contact-form', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinute(5)->by($this->guestThrottleKey($request, 'contact', [
                $request->input('email'),
            ]))
        ));

        RateLimiter::for('registration', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(10, 4)->by($this->guestThrottleKey($request, 'register', [
                $request->input('email'),
            ]))
        ));

        RateLimiter::for('course-payment', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(5, 6)->by($this->userThrottleKey($request, 'course-payment', [
                $this->routeIdentifier($request, 'course'),
            ]))
        ));

        RateLimiter::for('certificate-email', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(10, 5)->by($this->userThrottleKey($request, 'certificate-email', [
                $this->routeIdentifier($request, 'course'),
                $this->routeIdentifier($request, 'certificate'),
            ]))
        ));

        RateLimiter::for('forum-topic', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(10, 4)->by($this->userThrottleKey($request, 'forum-topic'))
        ));

        RateLimiter::for('forum-reply', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(5, 12)->by($this->userThrottleKey($request, 'forum-reply', [
                $this->routeIdentifier($request, 'topic'),
            ]))
        ));

        RateLimiter::for('forum-report', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(10, 6)->by($this->userThrottleKey($request, 'forum-report'))
        ));

        RateLimiter::for('pronunciation', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(5, 15)->by($this->userThrottleKey($request, 'pronunciation', [
                $this->routeIdentifier($request, 'exercise'),
            ]))
        ));

        RateLimiter::for('password-reset-submit', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(10, 5)->by($this->guestThrottleKey($request, 'password-reset-submit', [
                $request->input('email'),
            ]))
        ));

        RateLimiter::for('admin-two-factor', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(5, 5)->by($this->userThrottleKey($request, 'admin-two-factor'))
        ));

        RateLimiter::for('testimonial-submit', fn (Request $request) => $this->withWebThrottleResponse(
            Limit::perMinutes(30, 3)->by($this->userThrottleKey($request, 'testimonial-submit'))
        ));
    }

    protected function withWebThrottleResponse(Limit $limit): Limit
    {
        return $limit->response(function (Request $request, array $headers) {
            $message = __('Too many attempts. Please try again in a moment.');

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 429, $headers);
            }

            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['rate_limit' => $message])
                ->withHeaders($headers);
        });
    }

    protected function guestThrottleKey(Request $request, string $prefix, array $parts = []): string
    {
        return $this->throttleKey($prefix, array_merge($parts, [$request->ip()]));
    }

    protected function userThrottleKey(Request $request, string $prefix, array $parts = []): string
    {
        return $this->throttleKey($prefix, array_merge([
            $request->user()?->getAuthIdentifier(),
        ], $parts, [$request->ip()]));
    }

    protected function throttleKey(string $prefix, array $parts = []): string
    {
        $normalized = array_map(
            fn ($part) => is_string($part) ? Str::lower(trim($part)) : (string) $part,
            array_filter($parts, fn ($part) => filled($part))
        );

        return implode('|', array_merge([$prefix], $normalized));
    }

    protected function routeIdentifier(Request $request, string $parameter): ?string
    {
        $value = $request->route($parameter);

        if (is_object($value)) {
            if (method_exists($value, 'getRouteKey')) {
                return (string) $value->getRouteKey();
            }

            if (method_exists($value, 'getKey')) {
                return (string) $value->getKey();
            }
        }

        return filled($value) ? (string) $value : null;
    }
}
