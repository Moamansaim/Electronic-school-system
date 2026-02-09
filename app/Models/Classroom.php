<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'grade_level_id', 'teacher_id'];

    public function scopeSearch(Builder $builder, $term): void
    {
        $term = trim(string: $term);
        $builder->when($term, function ($query, $term) {
            $query->where('name', 'LIKE', "%{$term}%")
                ->orWhereHas('gradeLevel', function ($q) use ($term) {
                    $q->where('name', 'LIKE', "%{$term}%");
                });
        });
    }

    public function teacherAssignments(): HasMany
    {
        return $this->HasMany(TeacherAssignment::class);
    }

    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class)
            ->withDefault([
                'name' => 'لا يوجد مرحلة دراسية',
            ]);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'classroom_id');
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'classroom_id');
    }
}
