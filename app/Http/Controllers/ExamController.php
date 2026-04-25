<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Exam;
use App\Enums\ExamType;
use Illuminate\View\View;
use App\Http\Requests\ExamRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;


class ExamController extends Controller
{
    /**
     * عرض قائمة الاختبارات مع بيانات المادة والمعلم المرتبط بها.
     */
    public function index(): View
    {
        $exams = Exam::with(['subject', 'teacher', 'classrooms'])->paginate(10);
        return view('exam.index_exam', compact('exams'));
    }

    /**
     * عرض نموذج إنشاء اختبار جديد بناءً على المواد المسندة للمعلم الحالي.
     */
    public function create(): View
    {
        // جلب المواد المسندة للمعلم الذي قام بتسجيل الدخول
        $teacher_subjects = Auth::user()->teacher->teacherAssignments()->with('subject')->get();
        $exam_types = ExamType::cases();

        return view('exam.create_exam', compact('teacher_subjects', 'exam_types'));
    }

    /**
     * حفظ بيانات الاختبار الجديد في قاعدة البيانات.
     */
    public function store(ExamRequest $examRequest): RedirectResponse
    {
        Exam::create($examRequest->validated());
        return redirect()
            ->route('exams.index')
            ->with('success', 'تم إنشاء الإختبار بنجاح');
    }

    /**
     * عرض نموذج إضافة أسئلة لاختبار معين.
     */
    public function show(Exam $exam): View
    {
        $exam->load('subject');
        return view('question.create_question', compact('exam'));
    }

    /**
     * عرض قائمة الأسئلة التابعة لاختبار معين.
     */
    public function exam_questions($id): View
    {
        $exam = Exam::findOrFail($id);
        $questions = $exam->questions()->paginate(10);

        return view('question.exam_questions', compact('exam', 'questions'));
    }

    /**
     * عرض نموذج تعديل بيانات اختبار معين.
     */
    public function edit(Exam $exam): View
    {
        $teacher_subjects = Auth::user()->teacher->teacherAssignments()->with('subject')->get();
        $exam_types = ExamType::cases();

        return view('exam.edit_exam', compact('teacher_subjects', 'exam_types', 'exam'));
    }

    /**
     * تحديث بيانات اختبار موجود في قاعدة البيانات.
     */
    public function update(ExamRequest $examRequest, Exam $exam): RedirectResponse
    {
        // التحقق من نوع الاختبار؛ إذا كان نهائياً أو نصفي، يتم مسح قيمة الشهر
        if ($examRequest->exam_type === Exam::FINAL || $examRequest->exam_type === Exam::MIDTREM) {
            $exam->month = '';
        }

        $exam->update($examRequest->validated());
        return redirect()
            ->route('exams.index')
            ->with('success', 'تم تعديل الإختبار بنجاح');
    }

    /**
     * حذف اختبار معين من النظام.
     */
    public function destroy(Exam $exam): RedirectResponse
    {
        if ($exam->attempts()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف الاختبار حاليا, لوجود محاولات مسجلة للطلاب. يمكن حذفه فقط بعد ترفيع الطالب للمرحلة التالية ');
        }
        
        $exam->delete();
        
        return redirect()
            ->back()
            ->with('success', "تم حذف إختبار ({$exam->subject->name}) بنجاح");
    }
}