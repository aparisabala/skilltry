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
        Schema::create('hr_pay_grades', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('min_basic');
            $table->double('max_basic');
            $table->text('description')->nullable();
            $table->string('status', 10)->default('Active');
            $table->integer('serial')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_pay_grades');
    }
};
