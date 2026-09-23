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
        Schema::create('lib_districts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('lib_division_id')->constrained('lib_divisions')->cascadeOnDelete();
            $table->unique(['lib_division_id', 'name']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_districts');
    }
};
