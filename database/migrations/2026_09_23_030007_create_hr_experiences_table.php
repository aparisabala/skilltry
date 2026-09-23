<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->index();
            $table->string('organization');
            $table->string('position');
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->double('last_salary')->nullable();
            $table->string('leaving_reason')->nullable();
            $table->text('responsibilities')->nullable();
            $table->integer('serial')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_experiences');
    }
};
