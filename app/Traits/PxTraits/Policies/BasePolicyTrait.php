<?php

namespace App\Traits\PxTraits\Policies;

use App\Traits\PxTraits\Policies\Items\HrmUserPolicyTrait;
use App\Traits\PxTraits\Policies\Items\DataLibraryPolicyTrait;

trait BasePolicyTrait {

    use HrmUserPolicyTrait, \App\Traits\PxTraits\Policies\Items\ApiPolicyTrait, DataLibraryPolicyTrait;
    public function hrmPolicies(){
        return [
            [
                'name' => 'Admin Panel',
                'policies' => [
                    [
                        ...$this->hrmUserPolicies()
                    ],
                    [...$this->apiPolicies()],
                    [...$this->dataLibraryPolicies()]
                ]
            ]
        ];
    }
}
