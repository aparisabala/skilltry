<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ac_draft_balance_sheets', function (Blueprint $table) {
            $table->id();
            $table->date('tran_date');
            $table->string('tran_type', 15);
            $table->string('tran_method', 15);
            $table->foreignId('debit_to')->constrained('ac_ledgers');
            $table->foreignId('credit_to')->constrained('ac_ledgers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ac_draft_balance_sheets');
    }
};
