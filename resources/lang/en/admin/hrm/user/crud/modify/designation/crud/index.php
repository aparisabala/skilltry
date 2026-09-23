<?php
return [
    'pageTitle' => 'Doctor Designation',
    'pageSubTitle' => '',
    'add' => 'Add new designation',
    'update' => 'Update new designation',
    'breadCum' => [
        'title' => 'Modify Doctor',
        'b1' => 'Doctor Designation',
        'b2' => 'Manage Doctor Designation',
        'b3' => ''
    ],
    'nav' => [
        ...require resource_path('lang/en/admin/hrm/user/crud/modify/nav/index.php')
    ],
    'fields' => [
        'name' => 'Dgree',
        'passing_year' => 'Passing Year',
        'description' => 'Description',
    ],
    'table' => [
        'id' => 'ID',
        'serial' => 'Serial',
        'name' => 'Dgree',
        'passing_year' => 'Passing Year',
        'description' => 'Description',
        'created' => 'Created',
        'actions' => 'Actions',
    ]
];
