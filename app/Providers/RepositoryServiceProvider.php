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
use App\Repositories\Admin\DataLibrary\Bank\Crud\ILibBankRepository;
use App\Repositories\Admin\DataLibrary\Bank\Crud\LibBankRepository;
use App\Repositories\Admin\DataLibrary\Board\Crud\ILibBoardRepository;
use App\Repositories\Admin\DataLibrary\Board\Crud\LibBoardRepository;
use App\Repositories\Admin\DataLibrary\Degree\Crud\ILibDegreeRepository;
use App\Repositories\Admin\DataLibrary\Degree\Crud\LibDegreeRepository;
use App\Repositories\Admin\DataLibrary\Skill\Crud\ILibSkillRepository;
use App\Repositories\Admin\DataLibrary\Skill\Crud\LibSkillRepository;
use App\Repositories\Admin\DataLibrary\Division\Crud\ILibDivisionRepository;
use App\Repositories\Admin\DataLibrary\Division\Crud\LibDivisionRepository;
use App\Repositories\Admin\DataLibrary\District\Crud\ILibDistrictRepository;
use App\Repositories\Admin\DataLibrary\District\Crud\LibDistrictRepository;
use App\Repositories\Admin\DataLibrary\Thana\Crud\ILibThanaRepository;
use App\Repositories\Admin\DataLibrary\Thana\Crud\LibThanaRepository;
use App\Repositories\Admin\Category\Category\Crud\ILibCategoryRepository;
use App\Repositories\Admin\Category\Category\Crud\LibCategoryRepository;
use App\Repositories\Admin\Category\Subcategory\Crud\ILibSubcategoryRepository;
use App\Repositories\Admin\Category\Subcategory\Crud\LibSubcategoryRepository;
use App\Repositories\Admin\Category\Specialization\Crud\ILibSpecializationRepository;
use App\Repositories\Admin\Category\Specialization\Crud\LibSpecializationRepository;
use App\Repositories\Admin\Hrm\User\Crud\Modify\Designation\Crud\IAdminUserDesignationCrudRepository;
use App\Repositories\Admin\Hrm\User\Crud\Modify\Designation\Crud\AdminUserDesignationCrudRepository;
use App\Repositories\Admin\Hrm\User\Crud\Modify\DoctorOpdSlot\Crud\IAdminUserOpdSlotCrudRepository;
use App\Repositories\Admin\Hrm\User\Crud\Modify\DoctorOpdSlot\Crud\AdminUserOpdSlotCrudRepository;
use App\Repositories\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update\IAdminUserOpdFeesOpdFeesUpdateRepository;
use App\Repositories\Admin\Hrm\User\Crud\Modify\OpdFees\Form\Update\AdminUserOpdFeesOpdFeesUpdateRepository;
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
            $this->app->bind(abstract: ILibBankRepository::class, concrete: LibBankRepository::class);
            $this->app->bind(abstract: ILibBoardRepository::class, concrete: LibBoardRepository::class);
            $this->app->bind(abstract: ILibDegreeRepository::class, concrete: LibDegreeRepository::class);
            $this->app->bind(abstract: ILibSkillRepository::class, concrete: LibSkillRepository::class);
            $this->app->bind(abstract: ILibDivisionRepository::class, concrete: LibDivisionRepository::class);
            $this->app->bind(abstract: ILibDistrictRepository::class, concrete: LibDistrictRepository::class);
            $this->app->bind(abstract: ILibThanaRepository::class, concrete: LibThanaRepository::class);
            $this->app->bind(abstract: ILibCategoryRepository::class, concrete: LibCategoryRepository::class);
            $this->app->bind(abstract: ILibSubcategoryRepository::class, concrete: LibSubcategoryRepository::class);
            $this->app->bind(abstract: ILibSpecializationRepository::class, concrete: LibSpecializationRepository::class);
            $this->app->bind(abstract: \App\Repositories\Admin\Account\Ledger\Crud\IAcLedgerCrudRepository::class, concrete: \App\Repositories\Admin\Account\Ledger\Crud\AcLedgerCrudRepository::class);
            $this->app->bind(abstract: \App\Repositories\Admin\Account\Transaction\Crud\IAcDraftBalanceSheetCrudRepository::class, concrete: \App\Repositories\Admin\Account\Transaction\Crud\AcDraftBalanceSheetCrudRepository::class);
            $this->app->bind(abstract: \App\Repositories\Admin\Account\Transaction\Crud\ManageItem\Crud\IAcDraftBalanceSheetItemCrudRepository::class, concrete: \App\Repositories\Admin\Account\Transaction\Crud\ManageItem\Crud\AcDraftBalanceSheetItemCrudRepository::class);
            $this->app->bind(abstract: \App\Repositories\Admin\Account\Report\Balance\DataView\DisplayBalance\IDisplayBalanceDataViewRepository::class, concrete: \App\Repositories\Admin\Account\Report\Balance\DataView\DisplayBalance\DisplayBalanceDataViewRepository::class);
            $this->app->bind(abstract: \App\Repositories\Admin\Account\Report\Cashbook\Load\AccountCashbook\IAccountCashbookLoadRepository::class, concrete: \App\Repositories\Admin\Account\Report\Cashbook\Load\AccountCashbook\AccountCashbookLoadRepository::class);
            $this->app->bind(abstract: IAdminUserDesignationCrudRepository::class, concrete: AdminUserDesignationCrudRepository::class);
            $this->app->bind(abstract: IAdminUserOpdSlotCrudRepository::class, concrete: AdminUserOpdSlotCrudRepository::class);
            $this->app->bind(abstract: IAdminUserOpdFeesOpdFeesUpdateRepository::class, concrete: AdminUserOpdFeesOpdFeesUpdateRepository::class);
            $this->app->bind(abstract: \App\Repositories\Admin\DataLibrary\Department\Crud\ILibDepartmentRepository::class, concrete: \App\Repositories\Admin\DataLibrary\Department\Crud\LibDepartmentRepository::class);
        }
}
