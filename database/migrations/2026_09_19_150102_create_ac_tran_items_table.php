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
        Schema::create('ac_tran_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ac_balance_sheet_id')->nullable()->constrained('ac_balance_sheets');
            $table->foreignId('ac_cashbook_id')->constrained('ac_cashbooks');
            $table->string('folio_number')->nullable();
            $table->string('description');
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ac_tran_items');
    }
};
