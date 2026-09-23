<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_pf_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->index();
            $table->date('entry_date');
            /** Opening, Contribution, Withdrawal, Interest */
            $table->string('entry_type', 15);
            $table->double('employee_amount')->default(0);
            $table->double('employer_amount')->default(0);
            /** signed effect on the fund balance */
            $table->double('amount');
            $table->foreignId('hr_payslip_id')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_pf_entries');
    }
};
