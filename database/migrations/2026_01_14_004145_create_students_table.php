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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 30);
            $table->string('father_name', 30);
            $table->string('grandfather_name', 30);
            $table->string('family_name', 30);
            $table->date('date_of_birth');
            $table->string('national_id', 9)->unique();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('street')->nullable();
            $table->foreignId('grade_level_id')
                ->constrained('grade_levels', 'id')
                ->cascadeOnDelete();
            $table->foreignId('classroom_id')
                ->nullable()
                ->constrained('classrooms', 'id')
                ->nullOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
