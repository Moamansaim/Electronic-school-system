<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use App\Models\GradeLevel;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function index(Request $request): View
    {
        $classrooms = Classroom::with('gradeLevel', 'teacher:id,first_name,father_name,grandfather_name,family_name')
            ->select('id', 'name', 'grade_level_id', 'teacher_id', 'created_at')
            ->search($request->input('search'))
            ->latest()
            ->paginate(10);

        return view('classroom.index_classroom', compact('classrooms'));
    }

    public function create(): View
    {
        $grade_levels = $this->getGradeLevels();
        $teachers = $this->getTeachers();

        return view('classroom.create_classroom', compact('grade_levels', 'teachers'));
    }

    public function store(ClassroomRequest $request): RedirectResponse
    {
        try {
            Classroom::create($request->validated());

            return redirect()
                ->route('classrooms.index')
                ->with('success', 'تمت إضافة الصف الدراسي بنجاح.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ الصف الدراسي، يرجى المحاولة لاحقًا.');
        }
    }

    public function edit(Classroom $classroom): View
    {
        $grade_levels = $this->getGradeLevels();
        $teachers = $this->getTeachers();

        return view('classroom.edit_classroom', compact('classroom', 'grade_levels', 'teachers'));
    }

    public function update(ClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        try {
            $classroom->update($request->validated());

            return redirect()
                ->route('classrooms.index')
                ->with('success', 'تم تحديث الصف الدراسي بنجاح.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
            //  ->with('error', 'حدث خطأ أثناء تعديل الصف الدراسي، يرجى المحاولة لاحقًا.');
        }
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        try {
            $classroom->delete();

            return redirect()->back()->with('success', "تم حذف  ({$classroom->name}) بنجاح");
        } catch (Exception $e) {

            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء عملية حذف الصف الدراسي، يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * دالة مساعدة  لجلب المراحل الدراسية لتقليل التكرار
     */
    private function getGradeLevels()
    {
        return GradeLevel::select('id', 'name')->orderBy('name')->get();
    }

    private function getTeachers()
    {
        return Teacher::select(
            'id',
            'first_name',
            'father_name',
            'grandfather_name',
            'family_name'
        )->orderBy('first_name')->get();
    }
}
