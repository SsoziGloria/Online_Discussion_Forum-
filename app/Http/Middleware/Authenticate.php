<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $route = $request->route();

            if ($route && in_array($route->getName(), ['threads.create', 'categories.threads.create', 'threads.store'], true)) {
                $request->session()->flash('info', 'Please log in to create a new thread.');
            }

            return route('login');
        }
    }
}