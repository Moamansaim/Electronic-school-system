<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Exam extends Model

{
    use HasFactory;
    const FINAL = 'نهائي';
    const MIDTREM = 'نصفي';
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'description',
        'duration',
        'total_marks',
        'exam_type',
        'month'
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(
            Classroom::class,
            'exam_classrooms',
            'exam_id',
            'classroom_id',
        )->withPivot(['exam_id', 'classroom_id', 'start_time', 'end_time', 'is_published']);
    }
}