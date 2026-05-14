<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradeLevelRequest;
use App\Models\GradeLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeLevelController extends Controller
{
    /**
     * عرض قائمة المراحل الدراسية مع دعم خاصية البحث والترقيم.
     */
    public function index(Request $request): View
    {
        $grade_levels = GradeLevel::select('id', 'name', 'created_at')
            ->search($request->input('search'))
            ->latest()
            ->paginate(10)
            ->withQueryString(); // الحفاظ على معايير البحث عند التنقل بين الصفحات

        return view('grade_level.index_grade_level', compact('grade_levels'));
    }

    /**
     * عرض نموذج إنشاء مرحلة دراسية جديدة.
     */
    public function create(): View
    {
        return view('grade_level.create_grade_level');
    }

    /**
     * حفظ المرحلة الدراسية الجديدة في قاعدة البيانات.
     */
    public function store(GradeLevelRequest $gradeLevelRequest): RedirectResponse
    {
        GradeLevel::create($gradeLevelRequest->validated());
        return redirect()
            ->route('grade_levels.index')
            ->with('success', 'تم إضافة المرحلة الدراسية بنجاح');
    }

    /**
     * عرض نموذج تعديل مرحلة دراسية موجودة.
     */
    public function edit(GradeLevel $gradeLevel): View
    {
        return view('grade_level.edit_grade_level', compact('gradeLevel'));
    }

    /**
     * تحديث بيانات المرحلة الدراسية مع التحقق من عدم وجود ارتباطات.
     */
    public function update(GradeLevelRequest $gradeLevelRequest, GradeLevel $gradeLevel): RedirectResponse
    {
        $gradeLevel->update($gradeLevelRequest->validated());
        return redirect()
            ->route('grade_levels.index')
            ->with('success', 'تم تحديث المرحلة الدراسية بنجاح.');
    }

    /**
     * حذف المرحلة الدراسية من قاعدة البيانات.
     */
    public function destroy(GradeLevel $gradeLevel): RedirectResponse
    {
        $gradeLevel->delete();
        return redirect()
            ->back()
            ->with('success', "تم حذف المرحلة ({$gradeLevel->name}) بنجاح");
    }
}