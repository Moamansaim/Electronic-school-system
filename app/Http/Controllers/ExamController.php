<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Exam;
use App\Enums\ExamType;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\ExamRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $exams =  Exam::with(['subject', 'teacher'])->paginate(10);

        return view('exam.index_exam', compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $teacher_subjects = Auth::user()->teacher->teacherAssignments()->with('subject')->get();

        $exam_types = ExamType::cases();

        return view('exam.create_exam', compact('teacher_subjects', 'exam_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamRequest $examRequest): RedirectResponse
    {
        try {
            Exam::create($examRequest->validated());

            return redirect()
                ->route('exams.index')
                ->with('success', 'تم  إنشاء الإختبار   بنجاح');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إنشاء  الإختبار ، يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam): View
    {
        $exam->load('subject');

        return view('question.create_question', compact('exam'));
    }


    /**
     * Display the specified resource.
     */
    public function exam_questions($id): View
    {
        $exam = Exam::findOrFail($id);
        $questions = $exam->questions()->paginate(10);
        return view('question.exam_questions', compact('exam', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam): View
    {
        $teacher_subjects = Auth::user()->teacher->teacherAssignments()->with('subject')->get();

        $exam_types = ExamType::cases();

        return view('exam.edit_exam', compact('teacher_subjects', 'exam_types', 'exam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExamRequest $examRequest, Exam $exam): RedirectResponse
    {
        try {

            if ($examRequest->exam_type === Exam::FINAL || $examRequest->exam_type === Exam::MIDTREM) {
                $exam->month = '';
            }
            $exam->update($examRequest->validated());
            return redirect()
                ->route('exams.index')
                ->with('success', 'تم  تعديل الإختبار   بنجاح');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل  الإختبار ، يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam): RedirectResponse
    {
        try {
            $exam->delete();

            return redirect()->back()->with('success', "تم حذف إختبار ({$exam->subject->name}) بنجاح");
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء عملية حذف  الإختبار، يرجى المحاولة لاحقًا.');
        }
    }
}