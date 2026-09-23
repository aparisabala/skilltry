<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->index();
            $table->string('change_type')->default('Joined');
            $table->date('effective_date');
            $table->foreignId('lib_department_id')->nullable();
            $table->string('designation_title')->nullable();
            $table->double('salary')->nullable();
            $table->text('note')->nullable();
            $table->integer('serial')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_employments');
    }
};
