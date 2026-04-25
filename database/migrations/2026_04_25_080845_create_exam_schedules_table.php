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
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_title'); // مثال: جدول الاختبارات النهائية
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete(); // جلب المادة
            $table->date('exam_date'); // التاريخ
            $table->string('exam_day'); // اليوم
            $table->time('start_time'); // وقت البدء
            $table->time('end_time'); // وقت الانتهاء
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_schedules');
    }
};