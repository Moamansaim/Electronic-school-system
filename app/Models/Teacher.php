<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
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

    ];

    public function scopeSearch(Builder $builder, $trem)
    {

        $trem = trim($trem);
        $builder->when($trem, function ($query, $trem) {
            $query->where('first_name', 'LIKE', "{$trem}")
                ->orwhere('national_id', 'LIKE', "{$trem}");
        });
    }

    public function phoneNumbers()
    {
        return $this->HasMany(TeacherPhoneNumber::class);
    }

    public function teacherAssignments()
    {
        return $this->HasMany(TeacherAssignment::class);
    }

    public function classroom()
    {
        return $this->hasOne(Classroom::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->father_name} {$this->grandfather_name} {$this->family_name}";
    }
}
