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
        Schema::create('data_exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')
                ->constrained()
                ->cascadeOnDelete(); // جلب المادة
            $table->date('exam_date'); // التاريخ
            $table->string('exam_day'); // اليوم
            $table->time('start_time'); // وقت البدء
            $table->time('end_time'); // وقت الانتهاء
            $table->foreignId('exam_schedule_id')
                ->constrained('exam_schedules', 'id')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_exam_schedules');
    }
};