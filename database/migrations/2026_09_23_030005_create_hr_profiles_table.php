<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->unique();
            $table->string('employee_code', 50)->nullable();
            $table->string('gender', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('marital_status', 20)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('nid_no', 50)->nullable();
            $table->string('tin_no', 50)->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('emergency_name')->nullable();
            $table->string('emergency_phone', 30)->nullable();
            $table->string('emergency_relation', 50)->nullable();
            $table->date('join_date')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->foreignId('lib_department_id')->nullable();
            $table->string('designation_title')->nullable();
            $table->string('employment_type', 30)->default('Permanent');
            $table->string('employee_status', 30)->default('Active');
            $table->date('resign_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account', 60)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_profiles');
    }
};
