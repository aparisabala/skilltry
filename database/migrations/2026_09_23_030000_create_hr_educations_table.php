<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hr_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->index();
            $table->string('degree');
            $table->string('institute');
            $table->string('board_university')->nullable();
            $table->string('subject')->nullable();
            $table->string('passing_year')->nullable();
            $table->string('result')->nullable();
            $table->text('description')->nullable();
            $table->integer('serial')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_educations');
    }
};
