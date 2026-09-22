<?php

namespace App\Http\Controllers;

use App\Services\AdminPolicyService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Every controller action passes through here (web and /api/v1 alike), so
     * the admin permissions are checked in one place. See config/hrm.php.
     */
    public function callAction($method, $parameters)
    {
        $policy = app(AdminPolicyService::class);
        $request = request();
        $ability = $policy->denied($request, static::class, $method);

        if ($ability !== null) {
            $message = 'You do not have permission for this action';
            if ($policy->isApi($request)) {
                return response()->json(['success' => false, 'message' => $message, 'ability' => $ability], 403);
            }
            if ($request->expectsJson() || $request->ajax()) {
                // same envelope the panel's ajax layer already renders
                return response()->json([
                    'success' => false,
                    'noUpdate' => true,
                    'title' => '<span class="required fs-14">' . $message . '</span>',
                ]);
            }
            abort(403, $message);
        }

        return parent::callAction($method, $parameters);
    }
}
