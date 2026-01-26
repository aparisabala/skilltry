<?php

namespace App\Providers;

use App\Repositories\BaseRepository;
use App\Repositories\IBaseRepository;
use Illuminate\Support\ServiceProvider;
//vpx_imports
use App\Repositories\Admin\System\User\Policy\IAdminUserPolicyRepository;
use App\Repositories\Admin\System\User\Policy\AdminUserPolicyRepository;
use App\Repositories\Admin\System\User\UserRole\Crud\IAdminUserRoleCrudRepository;
use App\Repositories\Admin\System\User\UserRole\Crud\AdminUserRoleCrudRepository;
use App\Repositories\Admin\System\User\Crud\IAdminUserCrudRepository;
use App\Repositories\Admin\System\User\Crud\AdminUserCrudRepository;
class RepositoryServiceProvider extends ServiceProvider
{
        /**
         * Register any application services.
         */
        public function register(): void
        {
            $this->app->bind(abstract: IBaseRepository::class, concrete: BaseRepository::class);
            //vpx_attach
            $this->app->bind(abstract: IAdminUserPolicyRepository::class, concrete: AdminUserPolicyRepository::class);
            $this->app->bind(abstract: IAdminUserRoleCrudRepository::class, concrete: AdminUserRoleCrudRepository::class);
            $this->app->bind(abstract: IAdminUserCrudRepository::class, concrete: AdminUserCrudRepository::class);
        }
}
