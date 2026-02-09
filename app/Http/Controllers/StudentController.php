<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\ClassSchedule;
use App\Models\GradeLevel;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $students = Student::with(['phoneNumbers', 'gradeLevel:id,name', 'classroom:id,name'])->select(
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
            'grade_level_id',
            'classroom_id',
            'created_at'
        )->search($request->input('search'))
            ->latest()
            ->paginate(10);

        return view('student.index_student', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $grade_levels = GradeLevel::select('id', 'name')->get();

        return view('student.create_student', compact('grade_levels'));
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
    public function getDataByGrade($grade_level_id): JsonResponse
    {
        $grade_level = GradeLevel::findOrFail($grade_level_id);

        $grade_level->load(['classrooms', 'subjects']);

        return response()->json([
            'classrooms' => $grade_level->classrooms,
            'subjects' => $grade_level->subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $studentRequest): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $generated_school_id = strtolower(Str::random(4).''.$studentRequest->national_id);
            $generated_password = Str::random(12);

            $user = User::create([
                'school_id' => $generated_school_id,
                'password' => Hash::make($generated_password),
            ]);

            $studentRequest->merge(['user_id' => $user->id]);

            $data = $studentRequest->all();

            $student = Student::create($data);

            if ($studentRequest->has('phone_numbers')) {
                foreach ($studentRequest->phone_numbers as $phone) {
                    $student->phoneNumbers()->create([
                        'phone_number' => $phone,
                    ]);
                }
            }

            if ($studentRequest->has('subject_ids')) {
                $student->subjects()->sync(
                    $studentRequest->subject_ids
                );
            }

            DB::commit();

            return redirect()->route('students.index')
                ->with([
                    'success' => 'تم إضافة الطالب   بنجاح',
                    'generated_school_id' => $generated_school_id,
                    'generated_password' => $generated_password,

                ]);
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): View
    {
        $student = $student->load(['gradeLevel', 'classroom']);

        $rawSchedules = ClassSchedule::where('class_schedules.classroom_id', $student->classroom_id)
            ->join('teacher_assignments', function ($join) {
                $join->on('class_schedules.teacher_id', '=', 'teacher_assignments.teacher_id')
                    ->on('class_schedules.classroom_id', '=', 'teacher_assignments.classroom_id');
            })
            ->join('teachers', 'class_schedules.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'teacher_assignments.subject_id', '=', 'subjects.id')
            ->select(
                'class_schedules.day',
                'class_schedules.class_schedule',
                'subjects.name as subject_name',
                DB::raw("CONCAT_WS(' ', teachers.first_name, teachers.father_name,teachers.grandfather_name , teachers.family_name) as teacher_full_name")
            )
            ->get();

        $schedules = [];
        foreach ($rawSchedules as $item) {
            $schedules[$item->day][$item->class_schedule] = $item;
        }
        $days = ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'];
        $periods = ['الحصة الأولى', 'الحصة الثانية', 'الحصة الثالثة', 'الحصة الرابعة', 'الحصة الخامسة', 'الحصة السادسة'];

        return view('student.details', compact('student', 'schedules', 'days', 'periods'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        $grade_levels = $this->getGradeLevels();

        return view('student.edit_student', compact('student', 'grade_levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $studentRequest, Student $student): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $studentRequest->validated();

            $student->update($data);

            $student->phoneNumbers()->delete();

            if ($studentRequest->has('phone_numbers')) {
                $phone_numbers = array_unique($studentRequest->phone_numbers);
                foreach ($phone_numbers as $phone) {
                    $student->phoneNumbers()->create([
                        'phone_number' => $phone,
                    ]);
                }
            }

            $student->subjects()->sync($studentRequest->input('subject_ids', []));

            DB::commit();

            $full_name = $student->first_name.$student->father_name.' '.' '.$student->family_name;

            return redirect()->route('students.index')
                ->with('success', "تم تحديث بيانات الطالب ({$full_name}) بنجاح");
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل البيانات، يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        try {

            $student->delete();
            $full_name = $student->first_name.' '.$student->family_name;

            return redirect()->back()->with('success', "تم حذف الطالب ({$full_name}) بنجاح");
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء عملية حذف  الطالب يرجى المحاولة لاحقًا.');
        }
    }

    private function getGradeLevels()
    {
        return GradeLevel::select('id', 'name')->orderBy('name')->get();
    }
}
