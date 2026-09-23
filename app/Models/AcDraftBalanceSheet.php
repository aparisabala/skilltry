<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class AcDraftBalanceSheet extends Model
{
    use BaseTrait;
    protected $table = "ac_draft_balance_sheets";
    protected $fillable = [
        'tran_date',
        'tran_type',
        'tran_method',
        'debit_to',
        'credit_to',
    ];

    public function items()
    {
        return $this->hasMany(AcDraftBalanceSheetItem::class, 'ac_draft_transaction_id', 'id');
    }

    public function debit()
    {
        return $this->hasOne(AcLedger::class, 'id', 'debit_to');
    }

    public function credit()
    {
        return $this->hasOne(AcLedger::class, 'id', 'credit_to');
    }

    //vpx_attach
}
