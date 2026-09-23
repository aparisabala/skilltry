<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hr_payroll_id')->index();
            $table->foreignId('admin_user_id')->index();
            $table->double('basic')->default(0);
            $table->double('total_allowance')->default(0);
            $table->double('gross')->default(0);
            $table->integer('days_in_month')->default(30);
            $table->double('working_days')->default(0);
            $table->double('present_days')->default(0);
            $table->double('paid_leave_days')->default(0);
            $table->double('absent_days')->default(0);
            $table->integer('late_count')->default(0);
            $table->double('absent_deduction')->default(0);
            $table->double('late_deduction')->default(0);
            $table->double('pf_employee')->default(0);
            $table->double('pf_employer')->default(0);
            $table->double('component_deduction')->default(0);
            $table->double('advance_deduction')->default(0);
            $table->double('other_deduction')->default(0);
            $table->double('total_deduction')->default(0);
            $table->double('net')->default(0);
            $table->double('paid_amount')->default(0);
            /** Unpaid, Partial, Paid */
            $table->string('status', 10)->default('Unpaid');
            $table->json('breakdown')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_payslips');
    }
};
