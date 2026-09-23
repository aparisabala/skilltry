<?php

namespace App\Traits\PxTraits\Policies\Items;

trait AccountPolicyTrait {

    public function accountPolicies(){
        return [
            'name' => 'Account Management Policies',
            'policies' => [
                ['name' => 'Ac Ledger Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
            ]
        ];
    }
}
