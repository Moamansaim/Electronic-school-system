<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;


    public function scopeSearch(Builder $builder, $term): void
    {
        $term = trim(string: $term);
        $builder->when($term, function ($query, $term) {
            $query->where('question_text', 'LIKE', "%{$term}%");
        });
    }
    protected $fillable = ['exam_id', 'question_text', 'question_type', 'mark'];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function studentAnswer(): HasOne
    {
        return $this->hasOne(StudentAnswer::class);
    }
}