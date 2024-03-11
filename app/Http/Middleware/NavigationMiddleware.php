<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class NavigationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is authenticated, update navigation items for dashboard pages
        if (Auth::check() && ($request->routeIs('admin.*') || $request->routeIs('profile.show'))) {
            Inertia::share('navigationItems', static fn () => static::getDashboardItems($request));
            return $next($request);
        }

        Inertia::share('navigationItems', static fn () => static::getMainItems($request));

        return $next($request);
    }

    public static function getMainItems(Request $request)
    {
        // Get the user
        $user = $request->user();

        $items = collect([]);

        return $items->filter(fn ($item) => $item['active']);
    }

    public static function getDashboardItems(Request $request)
    {
        // Get the user
        $user = $request->user();

        $items = collect([]);

        return $items->filter(fn ($item) => $item['active']);
    }
}
