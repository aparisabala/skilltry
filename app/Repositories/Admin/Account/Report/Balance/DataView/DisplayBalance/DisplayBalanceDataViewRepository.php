<?php

namespace App\Repositories\Admin\Account\Report\Balance\DataView\DisplayBalance;

use App\Models\AcLedger;
use App\Repositories\BaseRepository;
use App\Traits\BaseTrait;

class DisplayBalanceDataViewRepository extends BaseRepository implements IDisplayBalanceDataViewRepository
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
        $data = [];
        $data['cash'] = [
            "name" => "Cash Balance",
            "items" => AcLedger::select(['id', 'name'])->withSum('cashCredit', 'total_amount')->withSum('cashDebit', 'total_amount')->where([['ledger_type', '=', 'cash']])->get()
        ];

        $data['bank'] = [
            "name" => "Bank Balance",
            "items" => AcLedger::select(['id', 'name'])->withSum('cashCredit', 'total_amount')->withSum('cashDebit', 'total_amount')->where([['ledger_type', '=', 'bank']])->get()
        ];
        return $data;
    }
}
