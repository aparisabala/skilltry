<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Server side enforcement of the admin permissions (Hrm > User policy).
 *
 * The permission slugs (e.g. lib_service_crud_view) are registered as Gates by
 * PxCommandService::registerPolicies. A controller action is checked against
 * the slug derived from its name, or the one in config('hrm.ability_overrides').
 * An action without a matching slug is not checked, so a missing policy can
 * never lock anybody out. See config/hrm.php for the roll out modes.
 */
class AdminPolicyService
{
    /** controller method => permission key(s) to try, in order */
    private const KEYS = [
        'index' => ['view', 'load'],
        'display' => ['view', 'load'],
        'list' => ['view'],
        'show' => ['view'],
        'create' => ['store'],
        'store' => ['store'],
        'edit' => ['edit'],
        'loadUpdate' => ['edit'],
        'update' => ['edit', 'update'],
        'updateList' => ['bulk_update'],
        'deleteList' => ['delete'],
    ];

    /**
     * Permission slug guarding this controller action, or null when unguarded.
     */
    public function abilityFor(string $controller, string $method): ?string
    {
        $short = class_basename($controller);

        $overrides = config('hrm.ability_overrides', []);
        if (array_key_exists("$short@$method", $overrides)) {
            return $overrides["$short@$method"] ?: null;
        }

        $keys = self::KEYS[$method] ?? null;
        if ($keys === null) {
            return null;
        }
        $base = Str::snake(Str::beforeLast($short, 'Controller'));
        foreach ($keys as $key) {
            foreach (["{$base}_{$key}", "{$base}_view_{$key}"] as $slug) {
                if (Gate::has($slug)) {
                    return $slug;
                }
            }
        }
        return null;
    }

    /**
     * The slug the current user is missing for this action, or null when the
     * request may go on (allowed, unguarded, not enforced in the current mode).
     */
    public function denied(Request $request, string $controller, string $method): ?string
    {
        $mode = config('hrm.permissions', 'api');
        $isHrm = str_starts_with($controller, 'App\\Http\\Controllers\\Admin\\Hrm\\')
            || str_starts_with($controller, 'App\\Http\\Controllers\\Api\\V1\\Admin\\Hrm\\');
        if ($mode === 'off' || !$isHrm) {
            return null;
        }
        if (!Auth::guard('admin')->check()) {
            return null; // authentication middleware answers for that
        }
        $ability = $this->abilityFor($controller, $method);
        if ($ability === null || Gate::allows($ability)) {
            return null;
        }

        $enforced = $mode === 'all' || ($mode === 'api' && $this->isApi($request));
        if (!$enforced) {
            Log::warning('[permissions] would deny', [
                'user' => Auth::guard('admin')->id(),
                'ability' => $ability,
                'action' => class_basename($controller) . '@' . $method,
                'path' => $request->path(),
            ]);
            return null;
        }
        return $ability;
    }

    public function isApi(Request $request): bool
    {
        return str_starts_with($request->path(), 'api/');
    }
}
