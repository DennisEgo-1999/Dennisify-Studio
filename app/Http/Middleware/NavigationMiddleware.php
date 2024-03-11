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
        Inertia::share('navigationItems', static fn () => static::getNavigationItems($request));

        return $next($request);
    }

    public static function getNavigationItems(Request $request)
    {
        // Get the user
        $user = $request->user();

        $items = collect([
            'login' => [
                'href' => route('login'),
                'label' => trans('spa.pages.users.login'),
                'active' => !$user,
            ],
            'register' => [
                'href' => route('register'),
                'label' => trans('spa.pages.users.register'),
                'active' => !$user,
            ],
            'dashboard' => [
                'href' => '/admin',
                'label' => trans('spa.pages.dashboard.label'),
                'active' => !!$user && $user->can('access-dashboard'),
                'as' => 'a'
            ],
            'profile' => [
                'href' => route('profile.show'),
                'label' => trans('spa.pages.profile.label'),
                'active' => !!$user,
            ],
            'logout' => [
                'href' => route('logout'),
                'label' => trans('spa.pages.users.logout'),
                'active' => !!$user,
            ],
        ]);

        return $items->filter(fn ($item) => $item['active']);
    }
}
