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
        Schema::create('ac_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ledger_type');
            $table->string('note')->nullable();
            $table->string('status', 7)->default('Active');
            $table->decimal('ac_balance', 15, 2)->default(0);
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->integer('serial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ac_ledgers');
    }
};
