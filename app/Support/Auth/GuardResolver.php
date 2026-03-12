<?php

namespace App\Support\Auth;

class GuardResolver
{
    // Map each guard to its dashboard route name.
    public static function guards(): array
    {
        return [
            'admin' => 'dashboard',
            'student' => 'student.dashboard',
            'teacher' => 'teacher.dashboard',
            'guardian' => 'guardian.dashboard',
        ];
    }

    // Check if the provided guard exists in the system map.
    public static function isValid(string $guard): bool
    {
        return array_key_exists($guard, self::guards());
    }

    // Resolve the dashboard route for a guard with admin fallback.
    public static function dashboardRoute(string $guard): string
    {
        return self::guards()[$guard] ?? 'dashboard';
    }

    // Detect the active guard for the current request context.
    public static function currentGuard(): ?string
    {
        $route = request()->route();

        // First, inspect auth middleware on the current route.
        if ($route !== null) {
            foreach ($route->gatherMiddleware() as $middleware) {
                if (! str_starts_with($middleware, 'auth:')) {
                    continue;
                }

                $guards = array_map('trim', explode(',', substr($middleware, 5)));

                foreach ($guards as $guard) {
                    if (self::isValid($guard) && auth()->guard($guard)->check()) {
                        return $guard;
                    }
                }
            }
        }

        // Then, try the framework default guard.
        $defaultGuard = auth()->getDefaultDriver();

        if (self::isValid($defaultGuard) && auth()->guard($defaultGuard)->check()) {
            return $defaultGuard;
        }

        // Final fallback across known guards.
        foreach (array_keys(self::guards()) as $guard) {
            if (auth()->guard($guard)->check()) {
                return $guard;
            }
        }

        return null;
    }
}
