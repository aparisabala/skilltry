<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->index();
            $table->foreignId('hr_leave_type_id');
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

    public function down(): void
    {
        Schema::dropIfExists('hr_leaves');
    }
};
