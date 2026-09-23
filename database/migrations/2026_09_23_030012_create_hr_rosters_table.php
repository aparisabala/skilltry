<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id');
            $table->date('roster_date');
            /** null = day off */
            $table->foreignId('lib_shift_id')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->unique(['admin_user_id', 'roster_date'], 'hr_roster_user_date_uq');
            $table->index('roster_date', 'hr_roster_date_idx');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_rosters');
    }
};
