<?php

namespace App\Http\Middleware\Admin;

use Closure;
use App\Models\AdminUser;
use App\Traits\BaseTrait;
class HasAdminUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    use BaseTrait;
    public function handle($request, Closure $next)
    {   
        if($request->has('auth_uuid')){
            $user = AdminUser::where([['uuid','=',$request->auth_uuid]])->first();
            if($user == null) {
                return $this->response(['type' => 'noUpdate', 'title' => 'No authenticated user found']);
            }
            $request->merge(['auth' => $user]);
        }
        return $next($request);
    }
}