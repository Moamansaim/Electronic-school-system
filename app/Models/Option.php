<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use SoftDeletes;
    protected $fillable = ['question_id', 'option_text', 'is_correct'];
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}