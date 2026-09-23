<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;

class AcTranItem extends Model
{
    use BaseTrait;
    protected $table = "ac_tran_items";
    protected $fillable = [
        'ac_balance_sheet_id',
        'ac_cashbook_id',
        'folio_number',
        'description',
        'amount',
    ];
}
