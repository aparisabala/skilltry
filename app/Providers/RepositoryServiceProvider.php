<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\IBaseRepository;
use Illuminate\Support\ServiceProvider;
//vpx_imports
use App\Repositories\Admin\Hrm\User\Policy\IAdminUserPolicyRepository;
use App\Repositories\Admin\Hrm\User\Policy\AdminUserPolicyRepository;
use App\Repositories\Admin\Hrm\User\UserRole\Crud\IAdminUserRoleCrudRepository;
use App\Repositories\Admin\Hrm\User\UserRole\Crud\AdminUserRoleCrudRepository;
use App\Repositories\Admin\Hrm\User\Crud\IAdminUserCrudRepository;
use App\Repositories\Admin\Hrm\User\Crud\AdminUserCrudRepository;
class RepositoryServiceProvider extends ServiceProvider
{
        /**
         * Register any application services.
         */
        public function register(): void
        {
            $this->app->bind(abstract: IBaseRepository::class, concrete: BaseRepository::class);
            //vpx_attach
            $this->app->bind(\App\Repositories\Api\V1\Admin\Dashboard\IAdminUserDashboardRepository::class, \App\Repositories\Api\V1\Admin\Dashboard\AdminUserDashboardRepository::class);
            $this->app->bind(\App\Repositories\Api\V1\Admin\Setup\IAdminUserSetupRepository::class, \App\Repositories\Api\V1\Admin\Setup\AdminUserSetupRepository::class);
            $this->app->bind(\App\Repositories\Api\V1\Admin\Setup\IAdminUserProfileUpdateRepository::class, \App\Repositories\Api\V1\Admin\Setup\AdminUserProfileUpdateRepository::class);
            $this->app->bind(\App\Repositories\Api\V1\Admin\Reset\IAdminUserResetRepository::class, \App\Repositories\Api\V1\Admin\Reset\AdminUserResetRepository::class);
            $this->app->bind(\App\Repositories\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem\IUpdatePolicyItemRepository::class, \App\Repositories\Admin\Hrm\User\Policy\Modal\UpdatePolicyItem\UpdatePolicyItemRepository::class);
            $this->app->bind(\App\Repositories\Api\V1\Admin\Auth\IAdminApiAuthRepository::class, \App\Repositories\Api\V1\Admin\Auth\AdminApiAuthRepository::class);
            $this->app->bind(abstract: IAdminUserPolicyRepository::class, concrete: AdminUserPolicyRepository::class);
            $this->app->bind(abstract: IAdminUserRoleCrudRepository::class, concrete: AdminUserRoleCrudRepository::class);
            $this->app->bind(abstract: IAdminUserCrudRepository::class, concrete: AdminUserCrudRepository::class);
        }
}
