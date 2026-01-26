<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class GradeLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    // الثوابت يفضل أن تكون بأسماء واضحة
    const GRADE_LEVEL = 'المرحلة';

    /**
     * نطاق البحث (Scope)
     * تم تعديل المسمى من searsh إلى search وتنظيف النص باستخدام trim
     */
    public function scopeSearch(Builder $builder, $term): void
    {
        $term = trim($term); // تنظيف كلمة البحث من الفراغات الزائدة

        $builder->when($term, function ($query, $term): void {
            $query->where('name', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Accessor لتنسيق تاريخ الإنشاء باللغة العربية وتوقيت غزة
     */
    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)
            ->timezone('Asia/Gaza')
            ->translatedFormat('l، j F Y g:i a');
    }

    /**
     * علاقة المرحلة بالصفوف الدراسية
     */
    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    /**
     * علاقة المرحلة بالمواد الدراسية
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'grade_level_id', 'id');
    }

    public function teacherAssignments()
    {
        return $this->HasMany(TeacherAssignment::class);
    }
}
