<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\QuestionRequest;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    // عرض قائمة الأسئلة مع تحميل الخيارات المرتبطة بها
    public function index(Request $request): View
    {
        $questions = Question::with('options')
            ->search($request->input('search'))
            ->latest()
            ->paginate(5);
        return view('question.index_question', compact('questions'));
    }

    // عرض نموذج إنشاء سؤال جديد
    public function create(): View
    {
        return view('question.create_question');
    }

    // حفظ السؤال الجديد وخياراته في قاعدة البيانات
    public function store(QuestionRequest $questionRequest): RedirectResponse
    {

        // dd($questionRequest);
        try {
            // استخدام المعاملات لضمان سلامة البيانات
            DB::transaction(function () use ($questionRequest) {

                $question = Question::create($questionRequest->validated());

                // التحقق من نوع السؤال لإضافة الخيارات إذا كان اختيار من متعدد
                if ($questionRequest->question_type === 'multiple_choice') {
                    foreach ($questionRequest->options as $index => $text) {
                        $question->options()->create([
                            'option_text' => $text,
                            'is_correct' => ($questionRequest->is_correct == $index) ? 1 : 0,
                        ]);
                    }
                }
            });

            return redirect()->back()->with('success', 'تمت إضافة السؤال بنجاح');
        } catch (\Exception $e) {
            // في حال حدوث خطأ، سيقوم الترانزكشن بعمل Rollback تلقائياً
            return redirect()
                ->back()
                ->with('error', $e->getMessage());

            //  ->with('error', 'حدث خطأ أثناء الحفظ، يرجى المحاولة لاحقاً.');
        }
    }

    // عرض نموذج تعديل سؤال معين
    public function edit(Question $question): View
    {
        $question->load('options');
        $exam = Exam::findOrFail($question->exam_id);
        return view('question.edit_question', compact('question', 'exam'));
    }

    // تحديث بيانات السؤال والخيارات المرتبطة به
    public function update(QuestionRequest $questionRequest, Question $question)
    {

        DB::transaction(function () use ($questionRequest, $question) {

            // 1. تحديث بيانات السؤال الأساسية
            $question->update($questionRequest->validated());

            // 2. حذف جميع الخيارات المرتبطة بهذا السؤال (تصفير الخيارات)
            $question->options()->delete();

            // 3. إذا كان النوع multiple_choice، نقوم بإضافة الخيارات المرسلة من النموذج كخيارات جديدة
            if ($questionRequest->question_type === 'multiple_choice' && !empty($questionRequest->options)) {

                foreach ($questionRequest->options as $index => $text) {
                    // منطق تحديد الإجابة الصحيحة:
                    // نفترض أن $questionRequest->is_correct يحتوي على 'index' الخيار الصحيح (مثلاً: 0 أو 1 أو 2)
                    $isCorrect = ($questionRequest->is_correct == $index) ? 1 : 0;

                    $question->options()->create([
                        'option_text' => $text,
                        'is_correct' => $isCorrect,
                    ]);
                }
            }
        });

        return redirect()->route('exam.questions', $question->exam_id)
            ->with('success', 'تم تحديث السؤال والخيارات بنجاح');
    }

    // حذف السؤال وجميع خياراته من قاعدة البيانات
    public function destroy(Question $question): RedirectResponse
    {
        $question->options()->delete();
        $question->delete();

        return back()->with('success', 'تم حذف السؤال بنجاح');
    }
}