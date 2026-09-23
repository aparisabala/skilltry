<?php

namespace App\Traits\PxTraits\Policies\Items;

trait DataLibraryPolicyTrait {

    public function dataLibraryPolicies(){
        return [
            'name' => 'Data Library Management Policies',
            'policies' => [
                ['name' => 'Lib Bank', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Lib Board', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Lib Degree', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Lib Skill', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Lib Division', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Lib District', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Lib Thana', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Lib Department', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
            ]
        ];
    }
}
