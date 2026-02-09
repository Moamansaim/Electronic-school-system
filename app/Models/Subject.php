<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'grade_level_id'];

    /**
     * نطاق البحث (Scope)
     * يدعم البحث في اسم المادة واسم المرحلة الدراسية المرتبطة
     */
    public function scopeSearch(Builder $builder, $term): void
    {
        // استخدام trim لتنظيف كلمة البحث من الفراغات الزائدة قبل المعالجة
        $term = trim(string: $term);

        $builder->when($term, function ($query, $term) {
            $query->where('name', 'LIKE', "%{$term}%")
                // ميزة ذكية: البحث باسم المرحلة الدراسية أيضاً
                ->orWhereHas('gradeLevel', function ($q) use ($term) {
                    $q->where('name', 'LIKE', "%{$term}%");
                });
        });
    }

    /**
     * علاقة المادة بالمرحلة الدراسية
     */
    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class, 'grade_level_id')
            ->withDefault([
                'name' => 'لا يوجد مرحلة دراسية',
            ]);
    }

    public function teacherAssignments(): HasMany
    {
        return $this->HasMany(TeacherAssignment::class);
    }

    /**
     * Accessor لتنسيق تاريخ الإنشاء
     */
    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)
            ->timezone('Asia/Gaza')
            ->translatedFormat('l، j F Y g:i a');
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'student_subjects',
            'subject_id',
            'student_id',
            'id',
            'id'
        );
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
    
}