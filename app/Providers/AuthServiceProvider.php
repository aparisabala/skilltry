<?php

namespace App\Providers;

use App\Services\PxCommandService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
//vpx_imports
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // HR abilities must exist before the first visit creates permission rows.
        $hrm = new class { use \App\Traits\PxTraits\Policies\Items\HrmUserPolicyTrait, \App\Traits\PxTraits\Policies\Items\ApiPolicyTrait, \App\Traits\PxTraits\Policies\Items\DataLibraryPolicyTrait, \App\Traits\PxTraits\Policies\Items\CategoryPolicyTrait; };
        foreach (array_merge($hrm->hrmUserPolicies()['policies'], $hrm->apiPolicies()['policies'], $hrm->dataLibraryPolicies()['policies'], $hrm->categoryPolicies()['policies']) as $policy) {
            foreach ($policy['keys'] as $action) {
                $slug = getPolicyKey(\Illuminate\Support\Str::class, $policy['name'].'_'.$action);
                \Illuminate\Support\Facades\Gate::define($slug, function ($user) use ($slug) {
                    return $user instanceof \App\Models\AdminUser && $user->hasPermission(
                        \App\Models\AdminUserPermission::where('slug', $slug)->value('user_access') ?? []
                    );
                });
            }
        }
        foreach (config('pxcommands.panels') as $panel => $panels) {
            if (Schema::hasTable($panel.'_user_roles')) {
                //vpx_attach
            app(PxCommandService::class)->registerPolicies(policyModelQuery: \App\Models\AdminUserPermission::class);
            }
        }
    }
}
