<?php

namespace App\Http\Controllers;


use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\Student;
use App\Models\StudentAnswer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ExamStudentController extends Controller
{
    //دالة لجلب الاختبارات المنشورة للطلاب 
    public function viewExamStudent()
    {
        $student = Student::with(['classroom.exams.attempts'])->where('user_id', auth()->id())->first();

        if (!$student) {
            return redirect()->back()->with("error", 'بيانات الطالب  لا توجد حالياً');
        }
        return view('student.student_exams', [
            'exams' => $student->classroom->exams
        ]);
    }

    // دالة بدأ جلسة الاختبار الخاصة بالطالب
    public function startExamStudent($id)
    {
        try {
            $student = Student::where('user_id', auth()->id())->first();


            $publishRecord = DB::table('exam_classrooms')
                ->where('exam_id', $id)
                ->where('classroom_id', $student->classroom_id)
                ->first();

            if (!$publishRecord) {
                return redirect()->back()->with("error", "هذا الامتحان غير متاح لصفك الدراسي حالياً.");
            }

            $startTime = $publishRecord->start_time ? \Carbon\Carbon::parse($publishRecord->start_time) : null;
            $endTime = $publishRecord->end_time ? \Carbon\Carbon::parse($publishRecord->end_time) : null;

            if (!$startTime || !$endTime) {
                return redirect()->back()->with("error", "خطأ في إعدادات وقت الامتحان، يرجى مراجعة الإدارة.");
            }

            $now = now();

            if ($now->lessThan($startTime)) {
                return redirect()->back()->with("error", "لم يحن موعد بدء الامتحان بعد.");
            }

            if ($now->greaterThan($endTime)) {
                return redirect()->back()->with("error", "انتهى الوقت المحدد لهذا الامتحان.");
            }

            $attempt = ExamAttempt::firstOrCreate(
                ['student_id' => $student->id, 'exam_id' => $id, 'status' => 'in progress'],
                ['start_at' => $now]
            );

            $exam = Exam::with(['questions.options', 'subject'])->findOrFail($id);

            // جلب الإجابات مع مراعاة وجود إجابة نصية أو اختيار معرف
            $student_answers = StudentAnswer::where('attempt_id', $attempt->id)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->question_id => $item->option_id];
                })
                ->toArray();


            return view('student.start_exam', [
                'exam' => $exam,
                'student_answers' => $student_answers, // مصفوفة مثل [question_id => answer_value]
                'questions' => $exam->questions,
                'attempt' => $attempt,
                'endTime' => $endTime->format('Y-m-d H:i:s')
            ]);
        } catch (Exception $e) {
            // return response()->json(['status' => 'success']);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    /**
     * حفظ الإجابة تلقائياً عبر AJAX
     */
    public function autoSaveAnswer(Request $request)
    {
        // 1. التحقق من صحة البيانات
        $request->validate([
            'attempt_id'  => 'required|exists:exam_attempts,id',
            'question_id' => 'nullable|exists:questions,id',
            'answer'      => 'nullable'
        ]);

        try {
            // 2. التحقق من حالة المحاولة (يجب أن تكون قيد التنفيذ)
            $attempt = ExamAttempt::where('id', $request->attempt_id)
                ->where('status', 'in progress')
                ->first();

            if (!$attempt) {
                return response()->json(['error' => 'الاختبار مغلق أو تم تسليمه مسبقاً'], 403);
            }

            // 3. جلب السؤال لمعرفة نوعه (MCQ أم مقالي)
            $question = Question::findOrFail($request->question_id);


            // 4. تنفيذ Update or Create
            // نستخدم نوع السؤال لتحديد أين نضع القيمة (option_id أم answer_text)
            StudentAnswer::updateOrCreate(
                [
                    'attempt_id'  => $request->attempt_id,
                    'question_id' => $request->question_id,
                ],
                [
                    'option_id' => $request->answer
                ]
            );

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            // return response()->json(['status' => 'success']);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //دالة تسليم الاختبار
    public function submitExam(Request $request)
    {
        // التحقق من وجود معرف الجلسة
        $request->validate([
            'attempt_id' => 'required|exists:exam_attempts,id'
        ]);

        DB::beginTransaction();
        try {
            $attemptId = $request->attempt_id;
            $attempt = ExamAttempt::findOrFail($attemptId);

            // 1. تحديث بيانات الجلسة الأساسية (تحويل الحالة ووقت التسليم)
            $attempt->status = 'submitted';
            $attempt->submitted_at = now();

            $currentTotalScore = 0;

            // 2. جلب جميع إجابات الطالب لهذه المحاولة مع بيانات الأسئلة والخيارات
            // نستخدم with لتقليل استعلامات قاعدة البيانات (Eager Loading)
            $studentAnswers = StudentAnswer::where('attempt_id', $attemptId)
                ->with(['question'])
                ->get();

            foreach ($studentAnswers as $answer) {
                $question = $answer->question;

                // أ. إذا كان السؤال اختيار من متعدد (تصحيح تلقائي)
                if ($answer->option_id) {
                    // جلب الخيار الصحيح لهذا السؤال
                    $correctOption = DB::table('options')
                        ->where('question_id', $question->id)
                        ->where('is_correct', 1)
                        ->first();

                    if ($correctOption && $answer->option_id  == $correctOption->id) {
                        $currentTotalScore += $question->mark;
                    }
                }
            }

            // 3. حفظ المجموع النهائي (الذي يحتوي حالياً على درجات الاختياري فقط)
            $attempt->final_score = $currentTotalScore;
            $attempt->save();

            DB::commit();
            return redirect()->route('exams.student')->with('success', 'تم تسليم الاختبار بنجاح.       ');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء معالجة التسليم: ' . $e->getMessage());
        }
    }


    public function studentExamMarks()
    {
        $student_marks = ExamAttempt::where('student_id', Auth::user()->student->id)
            ->with('exam')
            ->paginate(10);
        return view('student.student_marks', compact('student_marks'));
    }
}