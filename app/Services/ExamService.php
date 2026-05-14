<?php
namespace App\Services;

use App\Models\ExamAttempt;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class ExamService
{
    public function initializeAttempt(Student $student, $examId)
    {
        // 1. التحقق من صلاحية الامتحان لهذا الصف
        $publishRecord = DB::table('exam_classrooms')
            ->where('exam_id', $examId)
            ->where('classroom_id', $student->classroom_id)
            ->first();

        if (!$publishRecord) {
            throw new Exception("هذا الامتحان غير متاح لصفك الدراسي حالياً.");
        }

        // 2. التحقق من الوقت (Logic التنفيذي)
        $this->validateExamTime($publishRecord);

        // 3. إنشاء أو جلب محاولة الاختبار
        return ExamAttempt::firstOrCreate(
            ['student_id' => $student->id, 'exam_id' => $examId, 'status' => 'in progress'],
            ['start_at' => now()]
        );
    }

    private function validateExamTime($publishRecord)
    {
        $now = now();
        $startTime = Carbon::parse($publishRecord->start_time);
        $endTime = Carbon::parse($publishRecord->end_time);

        if ($now->lessThan($startTime)) {
            throw new Exception("لم يحن موعد بدء الامتحان بعد.");
        }

        if ($now->greaterThan($endTime)) {
            throw new Exception("انتهى الوقت المحدد لهذا الامتحان.");
        }
    }
}