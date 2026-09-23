<?php

namespace App\Traits\PxTraits\Policies\Items;

trait HrStaffPolicyTrait {

    public function hrStaffPolicies(){
        return [
            'name' => 'Hr Staff Management Policies',
            'policies' => [
                ['name' => 'Hr Education Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Experience Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Employment Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Advance Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Deduction Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Leave Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Leave Type Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Holiday Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Pay Component Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Pay Grade Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Admin User Designation Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Admin User Opd Slot Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Admin User Opd Fees Opd Fees Update', 'keys' => ['view','update']],
            ]
        ];
    }
}
