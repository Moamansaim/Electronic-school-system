<?php

namespace App\Rules;

use App\Models\ExamSchedule;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxOneRecord implements ValidationRule
{
    protected $ignorId;

    public function __construct($ignorId)
    {
        $this->ignorId = $ignorId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $query = ExamSchedule::query();

        if ($this->ignorId) {
            $query->where('id', "!=", $this->ignorId);
        }

        if ($query->exists()) {
            $fail('لا يمكن إضافة أكثر من جدول في النظام  حاليا.');
        }
    }
}