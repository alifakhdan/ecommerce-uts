<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware {
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string{
        if (! $request->expectsJson()) {
            // Jika yang mengakses adalah API/Flutter, langsung tolak dengan 401
            if ($request->is('api/*')) {
                abort(401, 'Unauthenticated');
            }
            // Jika yang mengakses web browser biasa, arahkan ke login
            return route('login');
        }
        return null;
    }
}
