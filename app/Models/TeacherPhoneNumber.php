<?php

namespace App\Models;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherPhonenumber extends Model
{
     use HasFactory ;
    protected $fillable = ['phone_number', 'teacher_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}