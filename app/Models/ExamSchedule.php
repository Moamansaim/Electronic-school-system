<?php

namespace App\Models;

use App\Models\DataExamSchedule;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $fillable = ['schedule_title'];


    public function dataExamSchedules()
    {
        return $this->hasMany(DataExamSchedule::class);
    }
}