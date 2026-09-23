<?php
return [
    'pageTitle' => 'Doctor Opd Fees',
    'pageSubTitle' => '',
    'add' => 'Add new role',
    'update' => 'Update new role',
    'breadCum' => [
        'title' => 'Modify Doctor',
        'b1' => 'Doctor Opd Fees',
        'b2' => 'Manage Doctor Opd Fees',
        'b3' => ''
    ],
    'nav' => [
        ...require resource_path('lang/en/admin/hrm/user/crud/modify/nav/index.php')
    ],
    'fields' => [
        'doctor_fees' => 'Doctor Fees',
        'hospital_fees' => 'Hospital Share',
        'service_fees' => 'Service Fees',
        'ipd_fees' => 'IPD Consultation Fee',
    ],
    'table' => [
        'id' => 'ID',
        'created' => 'Created',
        'actions' => 'Actions',
    ]
];
