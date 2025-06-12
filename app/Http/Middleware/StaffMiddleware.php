<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Versi Simple (jika Anda ingin yang sederhana)
        if (!Auth::check() || !Auth::user()->isStaff()) {
            abort(403, 'Hanya staff yang dapat mengakses halaman ini');
        }

        return $next($request);

        // Atau versi dengan redirect (lebih user-friendly)
        /*
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        if (!Auth::user()->isStaff()) {
            return redirect('/')->with('error', 'Hanya staff yang dapat mengakses halaman ini');
        }

        return $next($request);
        */
    }
}