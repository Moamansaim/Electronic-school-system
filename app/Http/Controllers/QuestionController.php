<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option; // تأكد من استيراد الموديل
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\QuestionRequest;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(): View
    {
        $questions = Question::with('options')->paginate();
        return view('question.index_question', compact('questions'));
    }

    public function create(): View
    {
        return view('question.create_question');
    }

    public function store(QuestionRequest $questionRequest): RedirectResponse
    {
        try {
            DB::transaction(function () use ($questionRequest) {
                $question = Question::create($questionRequest->validated());

                if ($questionRequest->question_type === 'multiple_choice') {
                    foreach ($questionRequest->options as $index => $text) {
                        $question->options()->create([
                            'option_text' => $text,
                            'is_correct'  => ($questionRequest->is_correct == $index) ? 1 : 0,
                        ]);
                    }
                }
            });

            return redirect()->back()->with('success', 'تمت إضافة السؤال بنجاح');
        } catch (\Exception $e) {
            // في حال حدوث خطأ، سيقوم الترانزكشن بعمل Rollback تلقائياً
            return redirect()->back()->with('error', 'حدث خطأ أثناء الحفظ، يرجى المحاولة لاحقاً.');
        }
    }

    public function edit(Question $question): View
    {
        // نستخدم العلاقة لجلب الخيارات
        $options = $question->options;
        return view('question.edit_question', compact('question', 'options'));
    }

    public function update(QuestionRequest $questionRequest, Question $question): RedirectResponse
    {
        try {
            DB::transaction(function () use ($questionRequest, $question) {
                $question->update($questionRequest->validated());

                if ($questionRequest->question_type === 'multiple_choice') {
                    $question->options()->delete();

                    foreach ($questionRequest->options as $index => $text) {
                        $question->options()->create([
                            'option_text' => $text,
                            'is_correct'  => ($questionRequest->is_correct == $index) ? 1 : 0,
                        ]);
                    }
                }
            });

            return back()->with('success', 'تم تحديث السؤال بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء التحديث، يرجى المحاولة لاحقاً.');
        }
    }

    public function destroy(Question $question): RedirectResponse
    {
        // بفضل العلاقة (إذا كنت قد أضفت onDelete('cascade') في الميجريشن)، 
        // سيتم حذف الخيارات تلقائياً، وإلا قم بحذفها يدوياً هنا
        $question->options()->delete();
        $question->delete();

        return back()->with('success', 'تم حذف السؤال بنجاح');
    }
}