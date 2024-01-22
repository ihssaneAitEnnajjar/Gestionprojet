<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckNonAdmin
{
    public function handle($request, Closure $next)
{
    if (auth()->user() && auth()->user()->role === 'admin') {
        return redirect('/'); // Redirigez les administrateurs vers une autre page
    }

    return $next($request);
}

}
