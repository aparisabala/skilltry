<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id');
            $table->date('work_date');
            $table->foreignId('lib_shift_id')->nullable();
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            /** Present, Late, Absent, Half Day */
            $table->string('status', 15)->default('Present');
            $table->integer('late_minutes')->default(0);
            $table->integer('early_minutes')->default(0);
            $table->integer('worked_minutes')->default(0);
            $table->string('source', 15)->default('Manual');
            $table->string('note')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->unique(['admin_user_id', 'work_date'], 'hr_att_user_date_uq');
            $table->index('work_date', 'hr_att_date_idx');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_attendances');
    }
};
