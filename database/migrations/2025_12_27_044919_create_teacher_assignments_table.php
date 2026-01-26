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
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')
                ->constrained('teachers', 'id')->cascadeOnDelete();
            $table->foreignId('classroom_id')
                ->constrained('classrooms', 'id')->cascadeOnDelete();
            $table->foreignId('subject_id')
                ->constrained('subjects', 'id')->cascadeOnDelete();
            $table->foreignId('grade_level_id')
                ->constrained('grade_levels', 'id')->cascadeOnDelete();
            $table->unique([
                'classroom_id',
                'subject_id',
                'grade_level_id',
            ], 'classroom_subject_grade_unique');
            $table->unique([
                'teacher_id',
                'classroom_id',
                'subject_id',
                'grade_level_id',
            ], 'teacher_assignment_full_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};
