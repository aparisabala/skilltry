<?php

namespace App\Traits\PxTraits\Policies;

use App\Traits\PxTraits\Policies\Items\HrmUserPolicyTrait;
use App\Traits\PxTraits\Policies\Items\DataLibraryPolicyTrait;
use App\Traits\PxTraits\Policies\Items\CategoryPolicyTrait;
use App\Traits\PxTraits\Policies\Items\AccountPolicyTrait;
use App\Traits\PxTraits\Policies\Items\HrStaffPolicyTrait;

trait BasePolicyTrait {

    use HrmUserPolicyTrait, \App\Traits\PxTraits\Policies\Items\ApiPolicyTrait, DataLibraryPolicyTrait, CategoryPolicyTrait, AccountPolicyTrait, HrStaffPolicyTrait;
    public function hrmPolicies(){
        return [
            [
                'name' => 'Admin Panel',
                'policies' => [
                    [
                        ...$this->hrmUserPolicies()
                    ],
                    [...$this->apiPolicies()],
                    [...$this->dataLibraryPolicies()],
                    [...$this->categoryPolicies()],
                    [...$this->accountPolicies()],
                    [...$this->hrStaffPolicies()]
                ]
            ]
        ];
    }
}
