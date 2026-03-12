<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\Auth\GuardResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(?string $guard = null): View
    {
        $requestedGuard = $guard ?? 'admin';

        abort_unless(GuardResolver::isValid($requestedGuard), 404);

        return view('auth.login', [
            'guard' => $requestedGuard,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, string $guard): RedirectResponse
    {
        $requestedGuard = $guard;

        abort_unless(GuardResolver::isValid($requestedGuard), 404);

        $request->authenticate($requestedGuard);

        $request->session()->regenerate();

        return redirect()->intended(route(GuardResolver::dashboardRoute($requestedGuard), absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request, string $guard): RedirectResponse
    {
        $requestedGuard = $guard;

        abort_unless(GuardResolver::isValid($requestedGuard), 404);
        abort_unless(Auth::guard($requestedGuard)->check(), 403);

        Auth::guard($requestedGuard)->logout();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
