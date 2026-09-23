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
        if (Schema::hasTable('admin_user_opd_slots')) {
            return;
        }
        Schema::create('admin_user_opd_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id');
            $table->string('year');
            $table->string('month');
            $table->string('day');
            $table->string('slot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_opd_slots');
    }
};
