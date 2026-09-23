<?php

namespace App\Traits\PxTraits\Policies\Items;

trait AccountPolicyTrait {

    public function accountPolicies(){
        return [
            'name' => 'Account Management Policies',
            'policies' => [
                ['name' => 'Ac Ledger Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Ac Draft Balance Sheet Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Ac Draft Balance Sheet Item Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Ac Report', 'keys' => ['view','excel']],
                ['name' => 'Display Balance Data View', 'keys' => ['view']],
                ['name' => 'Account Cashbook Load View', 'keys' => ['view']],
            ]
        ];
    }
}
