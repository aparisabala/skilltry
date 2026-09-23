<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
//vpx_imports
//crudDone
class AcDraftBalanceSheetItem extends Model
{
    use BaseTrait;
    protected $table = "ac_draft_balance_sheet_items";
    protected $fillable = [
        'ac_draft_transaction_id',
        'folio_number',
        'description',
        'amount',
    ];

    public function draft()
    {
        return $this->hasOne(AcDraftBalanceSheet::class, 'id', 'ac_draft_transaction_id');
    }
    //vpx_attach
}
