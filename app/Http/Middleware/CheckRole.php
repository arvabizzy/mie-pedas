<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
{
    // Jika user belum login atau role-nya tidak sesuai, tendang ke login
    if (!$request->user() || $request->user()->role !== $role) {
        return redirect('/');
    }

    return $next($request);
}
}
