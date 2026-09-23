<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Note: lib_department_id is stored as a plain unsignedBigInteger (no
     * ->constrained() FK) rather than ->foreignId(), because the parallel
     * LibDepartment workstream (migration timestamp 2026_09_23_020000) may
     * not have landed yet when this migration is authored/run. This avoids
     * a migration-order failure if hr_employments migrates before
     * lib_departments exists. The model still defines the belongsTo
     * relation; add an enforced FK constraint later once LibDepartment's
     * migration is confirmed present and always runs first.
     */
    public function up(): void
    {
        Schema::create('hr_employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->index();
            $table->string('change_type')->default('Joined');
            $table->date('effective_date');
            $table->unsignedBigInteger('lib_department_id')->nullable()->index();
            $table->string('designation_title')->nullable();
            $table->double('salary')->nullable();
            $table->text('note')->nullable();
            $table->integer('serial')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_employments');
    }
};
