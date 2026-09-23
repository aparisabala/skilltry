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
        Schema::create('ac_cashbooks', function (Blueprint $table) {
            $table->id();
            $table->date('tran_date');
            $table->string('tran_type', 15);
            $table->string('tran_method', 15);
            $table->foreignId('ac_ledger_id')->constrained('ac_ledgers');
            $table->foreignId('linked_to')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('entry_kind', 10)->default('general');
            $table->string('source', 30)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamps();
            $table->index('tran_date');
            $table->index('ac_ledger_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ac_cashbooks');
    }
};
