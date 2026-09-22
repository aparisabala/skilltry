<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminUser;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authenticates a request with the `admin_api` token guard.
 *
 * It implements AuthenticatesRequests on purpose: Laravel sorts all such
 * middleware to the front of the stack, and this one must run before the
 * controllers' own `auth:admin` middleware. The existing admin controllers,
 * repositories and policies read the logged in user from the session based
 * `admin` guard (`auth:admin`, `Auth::user()`, `auth()->guard('admin')`) and
 * from `$request->auth`, so the token's user is handed to that guard for the
 * lifetime of the request and all of that code keeps working unchanged.
 *
 * Parameter `allow-setup`: let users with an unfinished profile setup through
 * (used by /auth/me so the app can tell them to finish setup).
 */
class AuthenticateAdminApi implements AuthenticatesRequests
{
    public function handle(Request $request, Closure $next, ?string $option = null): Response
    {
        // errors (401/403/422/500) must always be JSON for API clients
        $request->headers->set('Accept', 'application/json');

        $guard = Auth::guard('admin_api');
        $guard->forgetUser();
        $user = $request->bearerToken() ? $guard->user() : null;
        if (!$user instanceof AdminUser) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }
        if ($user->status == 'Disabled') {
            return response()->json(['success' => false, 'message' => 'Account disabled'], 403);
        }
        if (!app(\App\Services\ApiAccessService::class)->canUseApi($user)) {
            return response()->json(['success' => false, 'message' => 'Mobile API access is not enabled for your account'], 403);
        }

        $setupRoute = $request->is('api/v1/admin/setup/profile');
        if ($user->setup_done == 'no' && $option !== 'allow-setup' && !$setupRoute) {
            return response()->json(['success' => false, 'code' => 'setup_required', 'message' => 'Profile setup is required'], 403);
        }

        $previousGuard = Auth::getDefaultDriver();
        $previousUser = Auth::guard('admin')->getUser();
        Auth::guard('admin')->setUser($user);
        Auth::shouldUse('admin');
        // The web middleware must never resolve an actor supplied by the client.
        $request->merge(['auth' => $user, 'auth_uuid' => $user->uuid]);

        try {
            return $next($request);
        } finally {
            $previousUser ? Auth::guard('admin')->setUser($previousUser) : Auth::guard('admin')->forgetUser();
            Auth::shouldUse($previousGuard);
            $guard->forgetUser();
        }
    }
}
