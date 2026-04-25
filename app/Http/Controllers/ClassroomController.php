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
    /**
     * عرض قائمة الفصول الدراسية مع تفعيل خاصية البحث والترقيم.
     */
    public function index(Request $request): View
    {
        $classrooms = Classroom::with('gradeLevel', 'teacher:id,first_name,father_name,grandfather_name,family_name')
            ->select('id', 'name', 'grade_level_id', 'teacher_id', 'created_at')
            ->search($request->input('search'))
            ->latest()
            ->paginate(10);

        return view('classroom.index_classroom', compact('classrooms'));
    }

    /**
     * عرض نموذج إنشاء فصل دراسي جديد.
     */
    public function create(): View
    {
        $grade_levels = $this->getGradeLevels();
        $teachers = $this->getTeachers();

        return view('classroom.create_classroom', compact('grade_levels', 'teachers'));
    }

    /**
     * حفظ بيانات الفصل الدراسي الجديد في قاعدة البيانات.
     */
    public function store(ClassroomRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            // التأكد من ضبط قيمة teacher_id كـ null في حال عدم اختيار معلم
            if (empty($data['teacher_id'])) {
                $data['teacher_id'] = null;
            }

            Classroom::create($data);

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

    /**
     * عرض نموذج تعديل بيانات فصل دراسي محدد.
     */
    public function edit(Classroom $classroom): View
    {
        $grade_levels = $this->getGradeLevels();
        $teachers = $this->getTeachers();

        return view('classroom.edit_classroom', compact('classroom', 'grade_levels', 'teachers'));
    }

    /**
     * تحديث بيانات فصل دراسي معين في قاعدة البيانات.
     */
    public function update(ClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        try {
            $data = $request->validated();

            if (empty($data['teacher_id'])) {
                $data['teacher_id'] = null;
            }

            $classroom->update($data);

            return redirect()
                ->route('classrooms.index')
                ->with('success', 'تم تحديث الصف الدراسي بنجاح.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل الصف الدراسي، يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * حذف فصل دراسي من النظام.
     */
    public function destroy(Classroom $classroom): RedirectResponse
    {
        $classroom->delete();
        return redirect()->back()->with('success', "تم حذف ({$classroom->name}) بنجاح");
    }

    /**
     * دالة مساعدة لجلب قائمة المراحل الدراسية لتقليل التكرار.
     */
    private function getGradeLevels()
    {
        return GradeLevel::select('id', 'name')->orderBy('name')->get();
    }

    /**
     * دالة مساعدة لجلب قائمة المعلمين لتقليل التكرار.
     */
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