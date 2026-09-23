<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->index();
            $table->string('deduction_type')->default('Penalty');
            $table->double('amount');
            $table->date('deduction_month');
            $table->string('reason')->nullable();
            $table->integer('applied_payslip_id')->nullable();
            $table->integer('serial')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_deductions');
    }
};
