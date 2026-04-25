<?php

namespace App\Rules;

use Closure;
use App\Models\Question;
use App\Models\Exam;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckTotalMarks implements ValidationRule
{
    protected $examId;
    protected $questionId;

    // نمرر معرف الاختبار ومعرف السؤال (في حالة التحديث فقط)
    public function __construct($examId, $questionId = null)
    {
        $this->examId = $examId;
        $this->questionId = $questionId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. جلب درجة الاختبار الكلية
        $exam = Exam::findOrFail($this->examId);

        // 2. حساب مجموع درجات الأسئلة الأخرى (باستثناء السؤال الحالي إذا كنا في حالة تحديث)
        $currentTotal = Question::where('exam_id', $this->examId)
            ->when($this->questionId, function ($query) {
                return $query->where('id', '!=', $this->questionId);
            })
            ->sum('mark');

        // 3. التحقق: المجموع الحالي + الدرجة الجديدة المرسلة
        if (($currentTotal + $value) > $exam->total_marks) {
            $fail('إجمالي درجات الأسئلة يتجاوز الدرجة الكلية المسموحة للاختبار (' . $exam->total_marks . ').');
        }
    }
}