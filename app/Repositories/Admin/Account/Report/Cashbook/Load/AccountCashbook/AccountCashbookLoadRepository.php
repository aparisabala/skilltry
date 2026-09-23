<?php

namespace App\Repositories\Admin\Account\Report\Cashbook\Load\AccountCashbook;

use App\Models\AcCashbook;
use App\Repositories\BaseRepository;
use App\Traits\BaseTrait;

class AccountCashbookLoadRepository extends BaseRepository implements IAccountCashbookLoadRepository
{

    use BaseTrait;
    public function __construct() {}

    /**
     * Get the page default resource
     *
     * @param Request $request
     * @return array
     */
    public function index($request): array
    {
        return [];
    }

    /**
     * Load view data
     *
     * @param Request $request
     * @return array
     */
    public function display($request): array
    {
        $data['cd_cash'] = 0;
        $data['cd_bank'] = 0;
        $data['incomes'] = AcCashbook::where([['tran_method', '=', 'income']])->get();
        $data['expenses'] = AcCashbook::where([['tran_method', '=', 'expense']])->get();
        $data['bd_cash'] = 0;
        $data['bd_bank'] = 0;
        return $data;
    }
}
