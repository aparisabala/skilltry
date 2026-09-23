<?php
return [
    'pageTitle' => 'Doctor Opd Slot',
    'pageSubTitle' => '',
    'add' => 'Add new role',
    'update' => 'Update new role',
    'breadCum' => [
        'title' => 'Modify Doctor',
        'b1' => 'Doctor Opd Slot',
        'b2' => 'Manage Doctor Opd Slot',
        'b3' => ''
    ],
    'nav' => [
        ...require resource_path('lang/en/admin/hrm/user/crud/modify/nav/index.php')
    ],
    'fields' => [
        'year' => 'Year',
        'month' => 'Month',
        'day' => 'Day',
        'slot' => 'Slot Name'
    ],
    'table' => [
        'id' => 'ID',
        'year' => 'Year',
        'month' => 'Month',
        'day' => 'Day',
        'slot' => 'Slot Name',
        'created' => 'Created',
        'actions' => 'Actions',
    ]
];
