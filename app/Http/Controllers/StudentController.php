<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\GradeLevel;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = Student::with('phoneNumbers')->select(
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

        return view('student.index_student', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $studentRequest)
    {
        try {
            DB::beginTransaction();

            $data = $studentRequest->validated();

                        dd($data);


            $student = Student::create($data);

            if ($studentRequest->has('phone_numbers')) {
                foreach ($studentRequest->phone_numbers as $phone) {
                    $student->phoneNumbers()->create([
                        'phone_number' => $phone,
                    ]);
                }
            }

            if ($studentRequest->has('subject_ids')) {
                $student->subjects()->sync([
                    'subject_id' => $studentRequest->subject_ids
                ]);
            }


            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'تم إضافة الطالب   بنجاح');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('student.edit_student', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $studentRequest, Student $student)
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

            DB::commit();

            $full_name = $student->first_name . $student->father_name . ' ' . ' ' . $student->family_name;

            return redirect()->route('teachers.index')
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
    public function destroy(Student $student)
    {
        try {

            $student->delete();
            $full_name = $student->first_name . ' ' . $student->family_name;

            return redirect()->back()->with('success', "تم حذف الطالب ({$full_name}) بنجاح");
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء عملية حذف  الطالب يرجى المحاولة لاحقًا.');
        }
    }
}