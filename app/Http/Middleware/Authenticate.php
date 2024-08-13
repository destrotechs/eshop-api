<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // For API requests expecting JSON, return null
        if ($request->expectsJson()) {
            return null;
        }

        // For web requests, redirect to the login route
        return route('login');
    }
}
