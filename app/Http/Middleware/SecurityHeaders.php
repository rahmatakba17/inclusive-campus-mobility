<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SecurityHeaders Middleware
 *
 * Menambahkan HTTP Security Headers ke setiap response
 * untuk mencegah XSS, Clickjacking, MIME Sniffing, dan serangan lainnya.
 * (Redundansi dengan .htaccess untuk memastikan header tetap ada di semua environment)
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Cegah Clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Cegah MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Aktifkan XSS filter browser
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Kebijakan referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Blokir akses ke kamera/mikrofon
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self), payment=()');

        // Hapus header yang membocorkan identitas server
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
