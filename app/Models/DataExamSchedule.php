<?php

namespace App\Models;

use App\Models\ExamSchedule;
use Illuminate\Database\Eloquent\Model;

class DataExamSchedule extends Model
{

    protected $fillable = [
        'subject_id',
        'exam_date',
        'exam_day',
        'start_time',
        'end_time',
        'exam_schedule_id'
    ];

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}