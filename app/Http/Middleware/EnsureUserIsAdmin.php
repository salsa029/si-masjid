<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Memastikan user sudah login dan memiliki role admin
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        // Tendang ke halaman utama jika bukan admin
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses administrator.');
    }
}
