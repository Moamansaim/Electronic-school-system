<?php

namespace App\Http\Controllers;

use App\Enums\ClassSchedule;
use App\Enums\WeekDay;
use App\Http\Requests\ClassScheduleRequest;
use App\Models\ClassSchedule as ModelsClassSchedule;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $week_days = WeekDay::cases();
        $class_schedules = ClassSchedule::cases();

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
        $class_schedules = \App\Models\ClassSchedule::where('teacher_id', $teacher_id)
            ->get();

        return view('class_schedule.index_class_schedule', compact('class_schedules'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassSchedule $classSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassSchedule $classSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSchedule $classSchedule)
    {
        //
    }
}
