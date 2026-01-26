<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPhoneNumber extends Model
{
    protected $table = 'student_phonenumbers';

    protected $fillable = ['phone_number', 'student_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
