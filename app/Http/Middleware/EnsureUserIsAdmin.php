<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Izinkan hanya user dengan role admin; user lain diarahkan ke halaman public.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->hasRole('admin')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
