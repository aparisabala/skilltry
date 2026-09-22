<?php

namespace App\Traits\PxTraits\Policies;

use App\Traits\PxTraits\Policies\Items\HrmUserPolicyTrait;

trait BasePolicyTrait {

    use HrmUserPolicyTrait, \App\Traits\PxTraits\Policies\Items\ApiPolicyTrait;
    public function hrmPolicies(){
        return [
            [
                'name' => 'Admin Panel',
                'policies' => [
                    [
                        ...$this->hrmUserPolicies()
                    ],
                    [...$this->apiPolicies()]
                ]
            ]
        ];
    }
}
