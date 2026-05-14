<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Attendance extends Model
{
    protected $fillable = ['student_id', 'attendance_date', 'status'];

    protected $casts = [
        'attendance_date' => 'date'
    ];

    public function student(): BelongsTo
    {

        return $this->belongsTo(Student::class);
    }

    public function getStatusLableAttribute()
    {
        return match ($this->status) {
            'present' => 'حاضر ✅',
            'absent'  => 'غائب ❌',
            'late'    => 'متأخر ⏰',
            'excused' => 'غائب بعذر ⚠️',
            default   => 'غير محدد',
        };
    }
}