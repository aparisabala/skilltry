<?php

namespace App\Traits\PxTraits\Policies\Items;

trait ApiPolicyTrait {

    /**
     * Shown on Hrm > User policy. Slug: mobile_api_access
     * Controls who may use the mobile / external API (/api/v1) at all; what they
     * may do inside it is decided by the same permissions the web panel uses.
     */
    public function apiPolicies(){
        return [
            'name' => 'Mobile API Policies',
            'policies' => [
                [
                    'name' => 'Mobile Api',
                    'keys' => ['access']
                ],
            ]
        ];
    }
}
