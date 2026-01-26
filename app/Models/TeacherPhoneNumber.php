<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPhonenumber extends Model
{
    protected $fillable = ['phone_number', 'teacher_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
