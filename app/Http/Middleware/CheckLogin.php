<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogin
{
    public function handle($request, Closure $next)
    {
        dd(
            'CHECK LOGIN',
            session()->all(),
            $request->url()
        );
    }


}
