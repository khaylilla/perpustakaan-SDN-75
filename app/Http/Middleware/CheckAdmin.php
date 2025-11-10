<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah session is_admin ada
        if (!$request->session()->has('is_admin') || !$request->session()->get('is_admin')) {
            return redirect()->route('login')->withErrors(['npm' => 'Silakan login sebagai admin terlebih dahulu.']);
        }

        return $next($request);
    }
}
