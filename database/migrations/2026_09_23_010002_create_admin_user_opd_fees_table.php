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
        if (Schema::hasTable('admin_user_opd_fees')) {
            return;
        }
        Schema::create('admin_user_opd_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id');
            $table->integer('doctor_fees')->default(0);
            $table->integer('hospital_fees')->default(0);
            $table->integer('service_fees')->default(0);
            $table->integer('ipd_fees')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_opd_fees');
    }
};
