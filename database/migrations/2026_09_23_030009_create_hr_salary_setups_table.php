<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_salary_setups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->unique();
            $table->foreignId('hr_pay_grade_id')->nullable();
            $table->double('basic')->default(0);
            $table->date('effective_from')->nullable();
            $table->double('pf_employee_percent')->default(0);
            $table->double('pf_employer_percent')->default(0);
            $table->string('deduct_absent', 3)->default('No');
            $table->string('late_fee_applies', 3)->default('Yes');
            $table->string('payment_method', 30)->default('Cash');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_salary_setups');
    }
};
