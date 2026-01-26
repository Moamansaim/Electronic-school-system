<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'classroom_id'
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

    public function phoneNumbers()
    {
        return $this->HasMany(StudentPhoneNumber::class);
    }


    public function subjects()
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
}