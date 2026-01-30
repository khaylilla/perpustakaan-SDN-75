<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MultiAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login (di salah satu dari 3 model: User, Guru, atau Umum)
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Cek apakah session login_as ada
        if (!session('login_as')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
