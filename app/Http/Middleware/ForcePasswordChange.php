<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->force_password_change && ! $request->routeIs('password.change.form') && ! $request->routeIs('password.change.update')) {
                return redirect()->route('password.change.form');
            }
        }
        return $next($request);
    }
}
