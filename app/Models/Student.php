<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'father_name',
        'grandfather_name',
        'family_name',
        'date_of_birth',
        'national_id',
        'city',
        'district',
        'street',
        'grade_level_id',
        'classroom_id',
        'user_id',
        'school_id',
    ];

    public function scopeSearch(Builder $builder, $trem)
    {

        $trem = trim($trem);
        $builder->when($trem, function ($query, $trem) {
            $query->where(function ($q) use ($trem) {
                $q->where('first_name', 'LIKE', "{$trem}")
                    ->orwhere('father_name', 'LIKE', "{$trem}")
                    ->orwhere('grandfather_name', 'LIKE', "{$trem}")
                    ->orwhere('family_name', 'LIKE', "{$trem}")
                    ->orwhere('national_id', 'LIKE', "{$trem}");
            });
        });
    }

    public function phones(): HasMany
    {
        return $this->HasMany(StudentPhoneNumber::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'student_subjects',
            'student_id',
            'subject_id',
            'id',
            'id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->father_name} {$this->grandfather_name} {$this->family_name}";
    }

   
}