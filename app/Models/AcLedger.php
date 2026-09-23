<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class AcLedger extends Model
{
    use BaseTrait;
    protected $table = "ac_ledgers";
    protected $fillable = [
        'name',
        'ledger_type',
        'note',
        'opening_balance',
        'serial'
    ];

    /** an empty opening balance is 0 */
    public function setOpeningBalanceAttribute($value): void
    {
        $this->attributes['opening_balance'] = $value === null || $value === '' ? 0 : $value;
    }

    public function cashCredit()
    {
        return $this->hasMany(AcCashbook::class, 'ac_ledger_id', 'id')->where([['tran_method', '=', 'income']]);
    }

    public function cashDebit()
    {
        return $this->hasMany(AcCashbook::class, 'ac_ledger_id', 'id')->where([['tran_method', '=', 'expense']]);
    }

    //vpx_attach
}
