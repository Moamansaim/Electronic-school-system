<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeLevelRequest;
use App\Models\GradeLevel;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeLevelController extends Controller
{
    /**
     * عرض المراحل الدراسية مع خاصية البحث
     */
    public function index(Request $request): View
    {
        $grade_levels = GradeLevel::select('id', 'name', 'created_at')
            ->search($request->input('search'))
            ->latest()
            ->paginate(10)
            ->withQueryString(); // للحفاظ على كلمة البحث عند التنقل بين الصفحات

        return view('grade_level.index_grade_level', compact('grade_levels'));
    }

    public function create(): View
    {
        return view('grade_level.create_grade_level');
    }

    public function store(GradeLevelRequest $gradeLevelRequest): RedirectResponse
    {

        try {
            // استخدام البيانات الموثقة مباشرة من الـ Request
            GradeLevel::create($gradeLevelRequest->validated());

            return redirect()
                ->route('grade_levels.index')
                ->with('success', 'تم إضافة المرحلة الدراسية بنجاح');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ المرحلة الدراسية، يرجى المحاولة لاحقًا.');
        }
    }

    public function edit(GradeLevel $gradeLevel): View
    {
        return view('grade_level.edit_grade_level', compact('gradeLevel'));
    }

    public function update(GradeLevelRequest $gradeLevelRequest, GradeLevel $gradeLevel): RedirectResponse
    {

        try {
            if ($gradeLevel->subjects()->exists()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'عذراً، لا يمكن تعديل هذه المرحلة لوجود مواد دراسية مرتبطة بها.');
            }
            $gradeLevel->update($gradeLevelRequest->validated());

            return redirect()
                ->route('grade_levels.index')
                ->with('success', 'تم تحديث المرحلة الدراسية بنجاح.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل المرحلة الدراسية، يرجى المحاولة لاحقًا.');
        }
    }

    public function destroy(GradeLevel $gradeLevel): RedirectResponse
    {
        try {
            $gradeLevel->delete();

            return redirect()->back()->with('success', "تم حذف المرحلة ({$gradeLevel->name}) بنجاح");
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء عملية حذف المرحلة الدراسية، يرجى المحاولة لاحقًا.');
        }
    }
}