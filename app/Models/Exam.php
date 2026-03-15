<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Exam extends Model

{
    use HasFactory, SoftDeletes;
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
}