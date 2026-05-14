<?php

namespace App\Models;

use Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'start_at',
        'submitted_at',
        'status',
        'final_score'
    ];

    protected $casts = [
        'final_score' => 'float'
    ];

    // public function teacher(): BelongsTo
    // {
    //     return $this->belongsTo(Exam::class);
    // }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class)->withDefault([
            'name' => null
        ]);
    }

    public function studentAnswer(): HasMany
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function getStartAtAttribute()
    {
        $value = $this->attribute['start_at'] ?? null;
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}