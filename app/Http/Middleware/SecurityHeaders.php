<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Add security headers to every response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = $this->generateNonce();
        $response = $next($request);

        if ($this->shouldInjectNonce($response)) {
            $response->setContent($this->injectNonceIntoMarkup($response->getContent(), $nonce));
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(self), geolocation=()');
        $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy($request, $nonce));
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        $response->headers->set('Origin-Agent-Cluster', '?1');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // HSTS is only safe to emit for production HTTPS traffic.
        if (app()->environment('production') && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    protected function contentSecurityPolicy(Request $request, string $nonce): string
    {
        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "object-src 'none'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:",
            "script-src-elem 'self' 'nonce-{$nonce}' https:",
            "script-src-attr 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline' https:",
            "img-src 'self' data: blob: https:",
            "font-src 'self' data: https:",
            "connect-src 'self' https: ws: wss:",
            "media-src 'self' blob: https:",
            "frame-src 'self' https:",
        ];

        if (app()->environment('production') && $request->isSecure()) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $directives);
    }

    protected function generateNonce(): string
    {
        return base64_encode(random_bytes(16));
    }

    protected function shouldInjectNonce(Response $response): bool
    {
        $contentType = (string) $response->headers->get('Content-Type', '');

        return str_contains($contentType, 'text/html') || $contentType === '';
    }

    protected function injectNonceIntoMarkup(string $markup, string $nonce): string
    {
        return preg_replace(
            '/<script\b(?![^>]*\bnonce=)([^>]*)>/i',
            '<script nonce="' . $nonce . '"$1>',
            $markup
        ) ?? $markup;
    }
}
