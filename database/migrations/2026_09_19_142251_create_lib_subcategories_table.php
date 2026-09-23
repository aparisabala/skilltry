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
        Schema::create('lib_subcategories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('lib_category_id')->constrained('lib_categories')->restrictOnDelete();
            $table->string('image')->nullable();
            $table->string('extension')->nullable();
            $table->integer('serial')->default(0);
            $table->timestamps();
            $table->unique(['lib_category_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_subcategories');
    }
};
