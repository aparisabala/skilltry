<?php

namespace App\Traits\PxTraits\Policies;

use App\Traits\PxTraits\Policies\Items\HrmUserPolicyTrait;
use App\Traits\PxTraits\Policies\Items\DataLibraryPolicyTrait;
use App\Traits\PxTraits\Policies\Items\CategoryPolicyTrait;

trait BasePolicyTrait {

    use HrmUserPolicyTrait, \App\Traits\PxTraits\Policies\Items\ApiPolicyTrait, DataLibraryPolicyTrait, CategoryPolicyTrait;
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
                    [...$this->categoryPolicies()]
                ]
            ]
        ];
    }
}
