<?php

namespace App\Rules;

use App\Models\GradeLevel;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckGradeLevelSubjects implements ValidationRule
{
    protected $grade_level_id;

    public function __construct($grade_level_id)
    {
        $this->grade_level_id = $grade_level_id;
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $grade_level_id = GradeLevel::findOrFail($this->grade_level_id);

        if ($grade_level_id && $grade_level_id->subjects()->exists()) {

            $fail('لا يمكن تعديل هذه المرحلة لوجود مواد مرتبطة بها');
        }
    }
}