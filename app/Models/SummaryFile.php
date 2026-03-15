<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SummaryFile extends Model
{
    protected $fillable = [
        'user_id', 
        'subject_id', 
        'file_name', 
        'file_path'
    ];

    // علاقة مع المادة
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}