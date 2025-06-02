<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNoConsent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Evita un bucle infinito en la propia página de consentimiento o logout.
        if ($user && !$user->consiente_datos && !$request->is('consentimiento') && !$request->is('logout')) {
            return redirect()->route('consentimiento');
        }

        return $next($request);
    }
}
