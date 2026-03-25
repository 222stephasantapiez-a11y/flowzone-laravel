<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->rol !== 'admin') {
            return match (Auth::user()->rol) {
                'empresa' => redirect()->route('empresa.dashboard'),
                default   => redirect()->route('home'),
            };
        }

        return $next($request);
    }
}
