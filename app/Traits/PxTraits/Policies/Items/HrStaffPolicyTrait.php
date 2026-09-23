<?php

namespace App\Traits\PxTraits\Policies\Items;

trait HrStaffPolicyTrait {

    public function hrStaffPolicies(){
        return [
            'name' => 'Hr Staff Management Policies',
            'policies' => [
                ['name' => 'Admin User Designation Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Admin User Opd Slot Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Admin User Opd Fees Opd Fees Update', 'keys' => ['view','update']],
            ]
        ];
    }
}
