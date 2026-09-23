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
        Schema::create('ac_draft_balance_sheet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ac_draft_transaction_id')->constrained('ac_draft_balance_sheets')->cascadeOnDelete();
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
        Schema::dropIfExists('ac_draft_balance_sheet_items');
    }
};
