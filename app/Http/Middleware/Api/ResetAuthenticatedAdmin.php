<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

class ResetAuthenticatedAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user('admin_api');
        // The mirrored reset flow applies only to its authenticated token owner.
        abort_if($request->filled('user_uuid') && $request->user_uuid !== $user->uuid, 403);
        abort_if($request->filled('email') && $request->email !== $user->email, 403);
        $request->merge(['user_uuid' => $user->uuid, 'email' => $user->email]);

        return $next($request);
    }
}
