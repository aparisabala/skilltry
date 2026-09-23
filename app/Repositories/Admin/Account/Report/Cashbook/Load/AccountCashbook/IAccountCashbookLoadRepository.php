<?php

namespace App\Repositories\Admin\Account\Report\Cashbook\Load\AccountCashbook;

interface IAccountCashbookLoadRepository
{
    public function index($request): array;
    public function display($request): array;
}
