<?php

namespace App\Http\Controllers;

use App\Enums\ClassSchedule;
use App\Enums\WeekDay;
use App\Http\Requests\ClassScheduleRequest;
use App\Models\Classroom;
use App\Models\ClassSchedule as ModelsClassSchedule;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create($teacher_id): View
    {

        $teacher = Teacher::with('teacherAssignments.classroom')->findOrFail($teacher_id);
        $week_days = $this->weekDays();
        $class_schedules = $this->classSchedule();

        return view('class_schedule.create_class_schedule', compact('teacher', 'week_days', 'class_schedules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassScheduleRequest $classScheduleRequest): RedirectResponse
    {

        try {
            ModelsClassSchedule::create($classScheduleRequest->validated());

            return redirect()
                ->back()
                ->with('success', 'تمت إضافة  الحصة الدراسية بنجاح.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حفظ  الحصة الدراسية يرجى المحاولة لاحقًا.');
        }
    }

    public function show($teacher_id)
    {
        if ($teacher_id) {
            $teacher = Teacher::findOrFail($teacher_id);
            $class_schedules = \App\Models\ClassSchedule::where('teacher_id', $teacher_id)
                ->get();
        } else {
            abort(404);
        }

        return view('class_schedule.index_class_schedule', compact('class_schedules', 'teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($class_schedule, $teacher_id): View
    {
        $teacher = Teacher::with('teacherAssignments.classroom')->findOrFail($teacher_id);
        $class_schedule = ModelsClassSchedule::findOrFail($class_schedule);
        // $classrooms = Classroom::select('id', 'name')->get();
        $week_days = $this->weekDays();
        $class_schedules = $this->classSchedule();

        return view('class_schedule.edit_class_schedule', compact(
            'teacher',
            'class_schedule',
            'week_days',
            'class_schedules',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassScheduleRequest $classScheduleRequest, ModelsClassSchedule $classSchedule): RedirectResponse
    {
        try {
            $classSchedule->update($classScheduleRequest->validated());

            $teacher_id = $classSchedule->teacher_id;

            return redirect()
                ->route('class-schedules.show', [
                    'class_schedule' => $classSchedule->id,
                    'teacher_id' => $teacher_id,
                ])
                ->with('success', 'تمت تعديل  الحصة الدراسية بنجاح.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل  الحصة الدراسية يرجى المحاولة لاحقًا.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModelsClassSchedule $classSchedule): RedirectResponse
    {
        try {

            $classSchedule->delete();

            return redirect()->back()->with('success', '  تمت عملية الحذف بنجاح');
        } catch (Exception $e) {

            return redirect()
                ->back()
                ->with('error', 'حدث خطأ أثناء عملية حذف الحصة الدراسية يرجى المحاولة لاحقًا.');
        }
    }

    public function weekDays(): array
    {
        return WeekDay::cases();
    }

    public function classSchedule(): array
    {
        return ClassSchedule::cases();
    }
}
