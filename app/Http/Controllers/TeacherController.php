<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherAssignmentRequest;
use App\Http\Requests\TeacherRequest;
use App\Models\GradeLevel;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teachers = Teacher::with('phoneNumbers:id,teacher_id,phone_number')->select(
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
            ->paginate(10);

        return view('teacher.index_teacher', compact('teachers'));
    }

    /**
     * Display the assignment page for a specific teacher.
     *
     * @param  int  $teacher_id
     * @return \Illuminate\View\View
     *
     * Loads the teacher by ID and fetches all grade levels.
     * Passes the teacher and grade levels to the view for assignment.
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
     * Fetch classrooms and subjects for a specific grade level.
     *
     * @param  int  $grade_level_id
     * @return \Illuminate\Http\JsonResponse
     *
     * Loads the grade level by ID along with related classrooms and subjects.
     * Returns the data as JSON for use in dynamic forms or AJAX requests.
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
     * Store a new teacher assignment.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * Validates the request data and creates a new TeacherAssignment record.
     * If successful, redirects to teachers index with a success message.
     * If an exception occurs, rolls back the transaction and returns back with input and error message.
     */
    public function storeAssignment(TeacherAssignmentRequest $teacherAssignmentRequest)
    {
        try {
            $teacher_assignment = $teacherAssignmentRequest->validated();

            TeacherAssignment::create($teacher_assignment);

            return redirect()
                ->route('teachers.index')
                ->with('success', 'تمت  تعيين المادة والصف  الدراسي للمعلم بنجاح.');
        } catch (Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ تعيين المادة والصف الدراسي، يرجى المحاولة لاحقًا.');
        }
    }

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
     * Show the form for
     *  creating a new resource.
     */
    public function create()
    {
        return view('teacher.create_teacher');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherRequest $teacherRequest)
    {

        try {
            DB::beginTransaction();

            $data = $teacherRequest->validated();

            $teacher = Teacher::create($data);

            if ($teacherRequest->has('phone_numbers')) {
                foreach ($teacherRequest->phone_numbers as $phone) {
                    $teacher->phoneNumbers()->create([
                        'phone_number' => $phone,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'تم إضافة المعلم   بنجاح');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ البيانات، يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return view('teacher.edit_teacher', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherRequest $teacherRequest, Teacher $teacher): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $teacherRequest->validated();

            $teacher->update($data);

            $teacher->phoneNumbers()->delete();

            if ($teacherRequest->has('phone_numbers')) {
                $phone_numbers = array_unique($teacherRequest->phone_numbers);
                foreach ($phone_numbers as $phone) {
                    $teacher->phoneNumbers()->create([
                        'phone_number' => $phone,
                    ]);
                }
            }

            DB::commit();

            $full_name = $teacher->first_name.' '.$teacher->family_name;

            return redirect()->route('teachers.index')
                ->with('success', "تم تحديث بيانات المعلم ({$full_name}) بنجاح");
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل البيانات، يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        try {

            $teacher->delete();
            $full_name = $teacher->first_name.' '.$teacher->family_name;

            return redirect()->back()->with('success', "تم حذف المعلم ({$full_name}) بنجاح");
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء عملية حذف  المعلم يرجى المحاولة لاحقًا.');
        }
    }

    public function destroyTeacherAssignment($teacher_assignment_id)
    {
        try {
            $teacher_assignment = TeacherAssignment::findOrFail($teacher_assignment_id);
            $teacher_assignment->delete();

            return redirect()->back()->with('success', 'تم حذف التعيين  بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء عملية حذف  التعيين يرجى المحاولة لاحقًا.');
        }
    }
}
