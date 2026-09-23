<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hr_payslip_id')->index();
            $table->foreignId('admin_user_id')->index();
            $table->double('amount');
            $table->string('method', 30)->default('Cash');
            $table->date('paid_on');
            $table->string('reference')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('paid_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_salary_payments');
    }
};
