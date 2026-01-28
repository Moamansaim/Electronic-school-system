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
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->string('class_schedule', 30);
            $table->foreignId('classroom_id')
                ->constrained('classrooms', 'id')
                ->cascadeOnDelete();
            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('teachers', 'id')
                ->nullOnDelete();
            $table->unique([
                'day',
                'class_schedule',
                'classroom_id',
                'teacher_id',
            ], 'class_schedule_unique');
            $table->unique([
                'day',
                'class_schedule',
                'classroom_id',
            ], 'day_unique');
            $table->unique([
                'day',
                'class_schedule',
                'teacher_id',
            ], 'class_schedule_day_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
