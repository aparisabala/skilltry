<?php

namespace App\Repositories\Admin\Account\Report\Balance\DataView\DisplayBalance;

interface IDisplayBalanceDataViewRepository
{
    public function index($request): array;
}
