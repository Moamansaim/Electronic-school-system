<?php

namespace App\Http\Controllers;

use App\Enums\StatusExam;
use App\Events\ExamPublished;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamClassroomController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. قواعد التحقق
        $rules = [
            'exam_id'       => 'required|exists:exams,id',
            'classroom_ids' => 'required|array|min:1',
            'classroom_ids.*' => 'exists:classrooms,id',
            'start_time'    => 'required|date|after_or_equal:now',
            'end_time'      => 'required|date|after:start_time',
            'is_published'  => 'nullable|boolean',
        ];

        // 2. رسائل الخطأ
        $messages = [
            'exam_id.required'          => 'يجب تحديد الاختبار المراد نشره.',
            'classroom_ids.required'    => 'يجب اختيار فصل دراسي واحد على الأقل.',
            'start_time.required'       => 'يجب تحديد وقت بداية الاختبار.',
            'start_time.after_or_equal' => 'يجب أن يكون وقت البدء الآن أو في وقت لاحق.',
            'end_time.required'         => 'يجب تحديد وقت نهاية الاختبار.',
            'end_time.after'            => 'يجب أن يكون وقت النهاية بعد وقت البدء.',
        ];

        try {
            // 3. تنفيذ التحقق
            $request->validate($rules, $messages);

            // 4. معالجة البيانات
            $exam = Exam::findOrFail($request->exam_id);
            $classrooms_ids = $request->classroom_ids;
            $publish_data = [];

            foreach ($classrooms_ids as $classrooms_id) {
                $publish_data[$classrooms_id] = [
                    'start_time'   => $request->start_time,
                    'end_time'     => $request->end_time,
                    'is_published' => $request->is_published ?? true,
                    // لإضافة التواريخ يدوياً إذا لم تكن مفعلة في الموديل:
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            // 5. الحفظ والمزامنة
            $exam->classrooms()->sync($publish_data);

            event(new ExamPublished($exam));

            return redirect()->back()->with('success', 'تمت عملية نشر الاختبار للفصول المختارة بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // في حال فشل الفلديشن، نرجع الأخطاء للمودال
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // في حال حدوث خطأ تقني (مثل مشكلة العمود المفقود في قاعدة البيانات)
            return redirect()
                ->back()
                ->with('error', 'عذراً، حدث خطأ أثناء النشر: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->classrooms()->detach();
        return redirect()->back()->with('success', 'تم إيقاف النشر لجميع الصفوف بنجاح');
    }

    //دالة جلب الصفوف المنشورة لها الاختبار
    public function showExamClassrooms($id)
    {
        $exam = Exam::with('classrooms')->findOrFail($id);
        return view('exam.exam_classroom', compact('exam'));
    }

    //دالة جلب طلاب الصفوف المنشورة لها الاختبار مع بيانات جلسة الاختبار الخاصة بكل طالب
    public function monitorExamStudent($exam_id, $classroom_id)
    {
        $students = Student::where('classroom_id', $classroom_id)
            ->with(['examAttempt' => function ($qurey) use ($exam_id) {
                $qurey->where('exam_id', $exam_id);
            }])->paginate(10);

        $status_exam = StatusExam::cases();
        return view('exam.status_exam', compact('students', 'status_exam'));
    }

    //دالة تعديل علامة الطالب
    public function updateScore(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:exam_attempts,id',
            'score' => 'required|numeric|min:0',
        ]);

        $attempt = ExamAttempt::with('exam')->findOrFail($request->attempt_id);

        // التحقق من النطاق برمجياً
        if ($request->score > $attempt->exam->total_marks) {
            return response()->json([
                'error' => false,
            ], 422);
        }

        $attempt->final_score = $request->score;
        $attempt->save();

        return response()->json(['success' => true]);
    }
}