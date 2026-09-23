<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * hr_leave_type_id references hr_leave_types (migration
     * 2026_09_23_020001_create_hr_leave_types_table.php), which was already present
     * in database/migrations when this migration was authored, so the FK constraint
     * is enforced here. This migration's timestamp (040002) sorts after 020001, so
     * hr_leave_types exists by the time this one runs.
     */
    public function up(): void
    {
        Schema::create('hr_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->index();
            $table->foreignId('hr_leave_type_id')->constrained();
            $table->date('from_date');
            $table->date('to_date');
            $table->string('reason')->nullable();
            $table->string('status')->default('Pending');
            $table->integer('days')->default(1);
            $table->integer('approved_by')->nullable();
            $table->integer('serial')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_leaves');
    }
};
