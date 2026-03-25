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
        if (!Auth::check() || Auth::user()->rol !== 'empresa') {
            abort(403, 'Acceso restringido al panel de empresa.');
        }

        return $next($request);
    }
}
