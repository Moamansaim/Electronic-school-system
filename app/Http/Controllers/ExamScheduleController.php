<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamScheduleRequest;
use App\Models\ExamSchedule;
use App\Models\Subject;


class ExamScheduleController extends Controller
{
    //  عرض كافة الجداول
    public function index()
    {
        $schedules = ExamSchedule::with('dataExamSchedules')->first();
        return view('exam.index_exam_schedule', compact('schedules'));
    }

    public function create()
    {
        $subjects = Subject::select('id', 'name')->get();
        $exam_schedule_title = ExamSchedule::select('id', 'schedule_title')->first();
        return view('exam.create_exam_schedule', compact('subjects', 'exam_schedule_title'));
    }

    /**
     * نستخدم StoreExamScheduleRequest هنا بدلاً من Request العادي
     */
    public function store(StoreExamScheduleRequest $request)
    {
        ExamSchedule::create($request->validated());
        return redirect()
            ->back()
            ->with('success', 'تم إضافة  جدول الاختبارات بنجاح');
    }

    // صفحة التعديل
    public function edit(ExamSchedule $examSchedule)
    {
        $subjects = Subject::select('id', 'name')->get();
        return view('exam.edit_exam_schedule', compact('examSchedule', 'subjects'));
    }

    // دالة تحديث البيانات
    public function update(StoreExamScheduleRequest $request, ExamSchedule $examSchedule)
    {
        $examSchedule->update($request->validated());
        return response()->json(['success' => true]);
    }

    // دالة الحذف
    public function destroy(ExamSchedule $examSchedule)
    {
        $examSchedule->delete();
        return redirect()
            ->back()
            ->with('success', 'تم حذف  جدول الاختبارات  بنجاح');
    }
}