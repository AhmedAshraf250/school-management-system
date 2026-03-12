<?php

namespace App\Http\Middleware;

use App\Support\Auth\GuardResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthenticatedByGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $allowedGuards = $this->validatedGuards($guards); // بتأكد الاول ان الجاردس المررة موجوده بالفعل داخل الهيلبر كلاس

        // بدون جارد في الراوت: افحص القائمة المسموحة كاملة if (! is_string($routeGuard)) return $allowedGuards;
        // مع جارد في الراوت: لازم يكون صالح ومسموح، ثم افحصه وحده
        $guardsToCheck = $this->guardsToCheck($request, $allowedGuards);

        foreach ($guardsToCheck as $guard) {
            if (! auth()->guard($guard)->check()) {
                continue;
            }

            return redirect()->route(GuardResolver::dashboardRoute($guard));
        }

        return $next($request);
    }

    private function validatedGuards(array $guards): array
    {
        if ($guards === []) {
            return ['admin'];
        }

        $invalidGuards = array_filter($guards, fn(string $guard): bool => ! GuardResolver::isValid($guard));

        if ($invalidGuards !== []) {
            abort(404);
        }

        return array_values(array_unique($guards));
    }

    private function guardsToCheck(Request $request, array $allowedGuards): array
    {
        $routeGuard = $request->route('guard');

        if (! is_string($routeGuard)) {
            return $allowedGuards;
        }

        if (! GuardResolver::isValid($routeGuard)) {
            abort(404);
        }

        if (! in_array($routeGuard, $allowedGuards, true)) {
            abort(404);
        }

        return [$routeGuard];
    }
}
