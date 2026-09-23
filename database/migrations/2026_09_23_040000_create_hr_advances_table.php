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
        Schema::create('hr_advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->index();
            $table->double('amount');
            $table->date('advance_date');
            $table->double('monthly_deduction');
            $table->string('reason')->nullable();
            $table->string('status')->default('Pending');
            $table->double('balance')->default(0);
            $table->integer('approved_by')->nullable();
            $table->date('approved_at')->nullable();
            $table->integer('serial')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_advances');
    }
};
