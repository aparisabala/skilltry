<?php

return [
    // Documentation visibility: local (default), public, or off.
    'api_docs' => env('HRM_API_DOCS', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Server side permission checks
    |--------------------------------------------------------------------------
    | The admin permissions (Hrm > User policy) were only used to hide menu
    | items and buttons. This switch makes the controllers enforce them.
    |
    |   off : no checks
    |   log : nothing is blocked, requests that WOULD be denied are logged
    |   api : /api/v1 is enforced, web is only logged        (default)
    |   all : web and API are both enforced
    |
    | Recommended roll out for the web panel: keep "api", read the
    | "[permissions] would deny" lines in storage/logs/laravel.log for a few
    | days, fix the roles that show up, then set HRM_PERMISSIONS=all.
    */
    'permissions' => env('HRM_PERMISSIONS', 'api'),

    /*
    |--------------------------------------------------------------------------
    | Ability overrides
    |--------------------------------------------------------------------------
    | "ControllerClass@method" => permission slug. Everything else is derived
    | from the controller name (LibServiceCrudController@store =>
    | lib_service_crud_store); a route whose derived slug does not exist is not
    | checked. Use false to leave a method open on purpose.
    */
    'ability_overrides' => [
        // HRM
        'AdminUserCrudController@index' => 'hrm_user_view',
        'AdminUserCrudController@list' => 'hrm_user_view',
        'AdminUserCrudController@create' => 'hrm_user_store',
        'AdminUserCrudController@store' => 'hrm_user_store',
        'AdminUserCrudController@edit' => 'hrm_user_edit',
        'AdminUserCrudController@update' => 'hrm_user_edit',
        'AdminUserCrudController@updateList' => 'hrm_user_edit',
        'AdminUserCrudController@deleteList' => 'hrm_user_delete',
        'AdminUserRoleCrudController@index' => 'hrm_user_roles_view',
        'AdminUserRoleCrudController@list' => 'hrm_user_roles_view',
        'AdminUserRoleCrudController@create' => 'hrm_user_roles_store',
        'AdminUserRoleCrudController@store' => 'hrm_user_roles_store',
        'AdminUserRoleCrudController@edit' => 'hrm_user_roles_edit',
        'AdminUserRoleCrudController@update' => 'hrm_user_roles_edit',
        'AdminUserRoleCrudController@updateList' => 'hrm_user_roles_edit',
        'AdminUserRoleCrudController@deleteList' => 'hrm_user_roles_delete',
        'AdminUserPolicyController@index' => 'hrm_user_policies_view',
        'AdminUserPolicyController@update' => 'hrm_user_policies_edit',
        'UpdatePolicyItemModalController@display' => 'hrm_user_policies_view',
        'UpdatePolicyItemModalController@update' => 'hrm_user_policies_edit',

        // Account
        'AcDraftBalanceSheetItemCrudController@saveAc' => 'ac_draft_balance_sheet_item_crud_edit',
        'AccountController@ledgers' => 'ac_ledger_crud_view',
        'AccountController@showLedger' => 'ac_ledger_crud_view',
        'AccountController@storeLedger' => 'ac_ledger_crud_store',
        'AccountController@updateLedger' => 'ac_ledger_crud_edit',
        'AccountController@statement' => 'ac_report_view',
        'AccountController@transactions' => 'ac_report_view',
        'AccountController@post' => 'ac_draft_balance_sheet_item_crud_edit',

    ],
];
