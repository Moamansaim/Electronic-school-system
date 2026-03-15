<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
     use HasFactory, SoftDeletes;
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
        'user_id',

    ];

    public function scopeSearch(Builder $builder, $trem): void
    {

        $trem = trim($trem);
        $builder->when($trem, function ($query, $trem) {
            $query->where('first_name', 'LIKE', "{$trem}")
                ->orwhere('national_id', 'LIKE', "{$trem}");
        });
    }

    public function phones(): HasMany
    {
        return $this->HasMany(TeacherPhoneNumber::class);
    }

    public function teacherAssignments(): HasMany
    {
        return $this->HasMany(TeacherAssignment::class);
    }

    public function classroom(): HasOne
    {
        return $this->hasOne(Classroom::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->father_name} {$this->grandfather_name} {$this->family_name}";
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classSchedules(): HasMany
    {
        return $this->HasMany(ClassSchedule::class);
    }

    public function exams(): HasMany
    {
        return $this->HasMany(Exam::class);
    }
}