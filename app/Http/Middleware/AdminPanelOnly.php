<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admins only get the admin panel: any other page sends them back to the dashboard.
 */
class AdminPanelOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isAdmin() && ! $request->routeIs('admin.*', 'logout', 'locale.switch', 'verification.*')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
