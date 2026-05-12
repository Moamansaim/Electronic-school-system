<?php

namespace App\Http\Controllers;

use App\Enums\ClassSchedule;
use App\Enums\WeekDay;
use App\Http\Requests\ClassScheduleRequest;
use App\Models\ClassSchedule as ModelsClassSchedule;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassScheduleController extends Controller
{
    /**
     * عرض قائمة الحصص الدراسية.
     */
    public function index() {}

    /**
     * عرض نموذج إنشاء حصة دراسية جديدة لمعلم معين.
     */
    public function create($teacher_id): View
    {
        $teacher = Teacher::with('teacherAssignments.classroom')->findOrFail($teacher_id);
        $week_days = $this->weekDays();
        $class_schedules = $this->classSchedule();

        return view('class_schedule.create_class_schedule', compact('teacher', 'week_days', 'class_schedules'));
    }

    /**
     * حفظ الحصة الدراسية الجديدة في قاعدة البيانات.
     */
    public function store(ClassScheduleRequest $classScheduleRequest): RedirectResponse
    {
    
            ModelsClassSchedule::create($classScheduleRequest->validated());

            return redirect()
                ->back()
                ->with('success', 'تمت إضافة الحصة الدراسية بنجاح.');
        
    }

    /**
     * عرض تفاصيل وجدول حصص معلم معين.
     */
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
     * عرض نموذج تعديل حصة دراسية معينة.
     */
    public function edit($class_schedule, $teacher_id): View
    {
        $teacher = Teacher::with('teacherAssignments.classroom')->findOrFail($teacher_id);
        $class_schedule = ModelsClassSchedule::findOrFail($class_schedule);
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
     * تحديث بيانات حصة دراسية محددة.
     */
    public function update(ClassScheduleRequest $classScheduleRequest, ModelsClassSchedule $classSchedule): RedirectResponse
    {
        
            $classSchedule->update($classScheduleRequest->validated());

            $teacher_id = $classSchedule->teacher_id;

            return redirect()
                ->route('class-schedules.show', [
                    'class_schedule' => $classSchedule->id,
                    'teacher_id' => $teacher_id,
                ])
                ->with('success', 'تمت تعديل الحصة الدراسية بنجاح.');
        } 


    /**
     * حذف حصة دراسية من النظام.
     */
    public function destroy(ModelsClassSchedule $classSchedule): RedirectResponse
    {
        
            $classSchedule->delete();

            return redirect()->back()->with('success', 'تمت عملية الحذف بنجاح');
        
    }

    /**
     * جلب أيام الأسبوع من الـ Enum الخاص بها.
     */
    public function weekDays(): array
    {
        return WeekDay::cases();
    }

    /**
     * جلب أوقات الحصص الدراسية من الـ Enum الخاص بها.
     */
    public function classSchedule(): array
    {
        return ClassSchedule::cases();
    }
}
