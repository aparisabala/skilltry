<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('holiday_date');
            $table->string('note')->nullable();
            $table->integer('serial')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_holidays');
    }
};
