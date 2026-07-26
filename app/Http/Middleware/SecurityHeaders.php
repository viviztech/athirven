<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baseline security headers for launch. The CSP allows exactly the
 * third-party origins this app already loads: Razorpay Checkout.js
 * (resources/views/frontend/payments/gateway-checkout.blade.php), Plausible
 * (components/frontend/layout.blade.php), and YouTube embeds
 * (components/frontend/youtube-embed.blade.php). 'unsafe-inline' stays in
 * script-src because the layout has legitimate inline scripts (dark-mode
 * toggle, service worker registration) — a nonce-based CSP would need to
 * touch every inline script and is left as a follow-up, not attempted here.
 *
 * The CSP is skipped outside production: Vite's dev server serves assets
 * cross-origin (http://[::1]:5173, per components/frontend/layout.blade.php's
 * @vite directive in local dev) with a live-reloading set of ports/protocols
 * that isn't worth chasing in a security policy — the built, same-origin
 * production assets are what the CSP is actually protecting.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        if (app()->environment('production')) {
            $response->headers->set('Content-Security-Policy', implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' https://checkout.razorpay.com https://plausible.io",
                "style-src 'self' 'unsafe-inline'",
                "img-src 'self' data: https: blob:",
                "font-src 'self' data:",
                "connect-src 'self' https://plausible.io https://api.razorpay.com",
                "frame-src 'self' https://www.youtube.com https://checkout.razorpay.com",
                "frame-ancestors 'none'",
            ]));
        }

        return $response;
    }
}
