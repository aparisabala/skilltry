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
        if (Schema::hasTable('admin_user_designations')) {
            return;
        }
        Schema::create('admin_user_designations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id');
            $table->string('name');
            $table->string('passing_year')->nullable();
            $table->text('description')->nullable();
            $table->integer('serial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_designations');
    }
};
