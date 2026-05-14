<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\GradeLevel;
use App\Models\Subject;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    /**
     * عرض قائمة المواد الدراسية مع البحث
     */
    public function index(Request $request): View
    {
        $subjects = Subject::with('gradeLevel:id,name')
            ->select('id', 'name', 'grade_level_id', 'created_at')
            ->search($request->input('search'))
            ->latest()
            ->paginate(10);

        return view('subject.index_subject', compact('subjects'));
    }

    /**
     * عرض صفحة إضافة مادة جديدة
     */
    public function create(): View
    {
        $grade_levels = $this->getGradeLevels();
        return view('subject.create_subject', compact('grade_levels'));
    }

    /**
     * حفظ مادة جديدة في قاعدة البيانات.
     */
    public function store(SubjectRequest $request): RedirectResponse
    {
        Subject::create($request->validated());
        return redirect()
            ->route('subjects.index')
            ->with('success', 'تمت إضافة المادة الدراسية بنجاح.');
    }

    /**
     * عرض تفاصيل مادة محددة.
     */
    public function show(Subject $subject): View
    {
        return view('subject.summary', compact('subject'));
    }


    /**
     * عرض صفحة تعديل مادة
     */
    public function edit(Subject $subject): View
    {
        $grade_levels = $this->getGradeLevels();
        return view('subject.edit_subject', compact('subject', 'grade_levels'));
    }

    /**
     * تحديث بيانات المادة الدراسية في قاعدة البيانات.
     */
    public function update(SubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($request->validated());
        return redirect()
            ->route('subjects.index')
            ->with('success', 'تم تحديث المادة الدراسية بنجاح.');
    }

    /**
     * حذف مادة من قاعدة البيانات.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();
        return redirect()
            ->back()
            ->with('success', "تم حذف مادة ({$subject->name}) بنجاح");
    }
    /**
     * جلب جميع المراحل الدراسية مرتبة بالاسم.
     */
    private function getGradeLevels()
    {
        return GradeLevel::select('id', 'name')->orderBy('name')->get();
    }
}