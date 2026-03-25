<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EsEmpresa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->rol !== 'empresa') {
            // Redirigir según el rol real en lugar de mostrar 403
            return match (Auth::user()->rol) {
                'admin'  => redirect()->route('admin.dashboard'),
                default  => redirect()->route('home'),
            };
        }

        return $next($request);
    }
}
