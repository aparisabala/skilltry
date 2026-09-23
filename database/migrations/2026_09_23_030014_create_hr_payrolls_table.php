<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_payrolls', function (Blueprint $table) {
            $table->id();
            $table->date('pay_month')->unique();
            $table->string('title');
            /** Draft, Approved, Partly Paid, Paid */
            $table->string('status', 15)->default('Draft');
            $table->integer('employees')->default(0);
            $table->double('total_gross')->default(0);
            $table->double('total_deduction')->default(0);
            $table->double('total_net')->default(0);
            $table->double('total_paid')->default(0);
            $table->foreignId('generated_by')->nullable();
            $table->foreignId('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_payrolls');
    }
};
