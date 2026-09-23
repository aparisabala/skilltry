<?php

namespace App\Traits\PxTraits\Policies\Items;

trait CategoryPolicyTrait {

    public function categoryPolicies(){
        return [
            'name' => 'Category Management Policies',
            'policies' => [
                ['name' => 'Lib Category', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Lib Subcategory', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
                ['name' => 'Lib Specialization', 'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']],
            ]
        ];
    }
}
