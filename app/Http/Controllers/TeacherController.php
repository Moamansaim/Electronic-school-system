<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherAssignmentRequest;
use App\Http\Requests\TeacherRequest;
use App\Models\GradeLevel;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    /**
     * عرض قائمة المعلمين.
     */
    public function index(Request $request)
    {
        $teachers = Teacher::with('phones:id,teacher_id,phone_number')->select(
            'id',
            'first_name',
            'father_name',
            'grandfather_name',
            'family_name',
            'date_of_birth',
            'national_id',
            'city',
            'district',
            'street',
            'created_at'
        )->search($request->input('search'))
            ->latest()
            ->paginate(10);

        return view('teacher.index_teacher', compact('teachers'));
    }

    /**
     * عرض صفحة تعيين المواد والصفوف للمعلم.
     *
     * @param  int  $teacher_id
     * @return \Illuminate\View\View
     */
    public function teacherAssignment($teacher_id)
    {
        $teacher = Teacher::findOrFail($teacher_id);
        $grade_levels = GradeLevel::select('id', 'name')->get();

        return view(
            'teacher.teacher_assignment',
            compact('grade_levels', 'teacher')
        );
    }

    /**
     * جلب الفصول والمواد بناءً على المرحلة الدراسية.
     *
     * @param  int  $grade_level_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDataByGrade($grade_level_id)
    {
        $grade_level = GradeLevel::findOrFail($grade_level_id);

        $grade_level->load(['classrooms', 'subjects']);

        return response()->json([
            'classrooms' => $grade_level->classrooms,
            'subjects' => $grade_level->subjects,
        ]);
    }

    /**
     * حفظ تعيين مادة وصف دراسي للمعلم.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAssignment(TeacherAssignmentRequest $teacherAssignmentRequest)
    {
        $teacher_assignment = $teacherAssignmentRequest->validated();
        TeacherAssignment::create($teacher_assignment);

        return redirect()
            ->route('teachers.index')
            ->with('success', 'تمت  تعيين المادة والصف  الدراسي للمعلم بنجاح.');
    }

    /**
     * عرض بيانات تعيينات المعلم.
     */
    public function dataTeacherAssignment($teacher_id)
    {

        $teacher = Teacher::with([
            'teacherAssignments.gradeLevel',
            'teacherAssignments.classroom',
            'teacherAssignments.subject',
        ])->findOrFail($teacher_id);

        return view('teacher.data_teacherAssignment', compact('teacher'));
    }

    /**
     * عرض نموذج إضافة معلم جديد.
     */
    public function create()
    {
        return view('teacher.create_teacher');
    }

    /**
     * حفظ معلم جديد في قاعدة البيانات.
     */
    public function store(TeacherRequest $teacherRequest): RedirectResponse
    {

        try {

            DB::beginTransaction();

            $generated_school_id = strtolower(Str::random(4) . '' . $teacherRequest->national_id);
            $generated_password = Str::random(12);

            $user = User::create([
                'school_id' => $generated_school_id,
                'password' => Hash::make($generated_password),
            ]);

            $teacherRequest->merge(['user_id' => $user->id]);

            $data = $teacherRequest->all();

            $teacher = Teacher::create($data);

            if ($teacherRequest->has('phone_numbers')) {
                foreach ($teacherRequest->phone_numbers as $phone) {
                    $teacher->phones()->create([
                        'phone_number' => $phone,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('teachers.index')
                ->with([
                    'success' => 'تم إضافة المعلم   بنجاح',
                    'generated_school_id' => $generated_school_id,
                    'generated_password' => $generated_password,

                ]);
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ البيانات، يرجى المحاولة لاحقًا. ');
        }
    }

    /**
     * عرض تفاصيل معلم محدد.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * عرض نموذج تعديل بيانات معلم.
     */
    public function edit(Teacher $teacher)
    {
        return view('teacher.edit_teacher', compact('teacher'));
    }

    /**
     * تحديث بيانات المعلم في قاعدة البيانات.
     */
    public function update(TeacherRequest $teacherRequest, Teacher $teacher): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $teacherRequest->validated();

            $teacher->update($data);

            $teacher->phones()->delete();

            if ($teacherRequest->has('phone_numbers')) {
                $phone_numbers = array_unique($teacherRequest->phone_numbers);
                foreach ($phone_numbers as $phone) {
                    $teacher->phones()->create([
                        'phone_number' => $phone,
                    ]);
                }
            }

            DB::commit();

            $full_name = $teacher->full_name;

            return redirect()->route('teachers.index')
                ->with('success', "تم تحديث بيانات المعلم ({$full_name}) بنجاح");
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل البيانات، يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * حذف معلم من قاعدة البيانات.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()
            ->back()
            ->with('success', "تم حذف المعلم ({$teacher->full_name}) بنجاح");
    }

    /**
     * حذف تعيين مادة أو صف دراسي للمعلم.
     */
    public function destroyTeacherAssignment($teacher_assignment_id)
    {
        $teacher_assignment = TeacherAssignment::findOrFail($teacher_assignment_id);
        $teacher_assignment->delete();

        return redirect()
            ->back()
            ->with('success', 'تم حذف التعيين  بنجاح');
    }
}