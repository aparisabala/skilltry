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
                // HR Setup flat CRUDs (lookup lists)
                ['name' => 'Lib Shift Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Paycomponent Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Paygrade Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Leavetype Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Holiday Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                // Employee profile nested history tables (tabs inside the Employee profile screen)
                ['name' => 'Hr Education Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Experience Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Employment Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Advance Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Deduction Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Hr Leave Crud', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                // HR Desk business screens
                ['name' => 'Hr Employee', 'keys' => ['view']],
                ['name' => 'Hr Profile', 'keys' => ['view','edit']],
                ['name' => 'Hr Salary Setup', 'keys' => ['view','edit']],
                ['name' => 'Hr Attendance', 'keys' => ['view','store']],
                ['name' => 'Hr Roster', 'keys' => ['view','store']],
                ['name' => 'Hr Payroll', 'keys' => ['view','generate','approve','pay']],
                ['name' => 'Hr Pf', 'keys' => ['view','store']],
                ['name' => 'Hr Report', 'keys' => ['view','excel']],
                ['name' => 'Hr Settings', 'keys' => ['view','edit']],
            ]
        ];
    }
}
