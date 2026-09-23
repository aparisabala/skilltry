<?php

namespace App\Models;

use App\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;

class AcCashbook extends Model
{
    use BaseTrait;
    protected $table = "ac_cashbooks";
    protected $fillable = [
        'tran_date',
        'tran_type',
        'tran_method',
        'ac_ledger_id',
        'linked_to',
        'total_amount',
        'entry_kind',
        'source',
        'source_id',
    ];
}
