<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use App\Http\Requests\StoreExamScheduleRequest;

class ExamScheduleController extends Controller
{
    // عرض كافة الجداول
    public function index()
    {
        $schedules = ExamSchedule::with('subject')->orderBy('exam_date', 'asc')->get();
        return view('exam.index_exam_schedule', compact('schedules'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('exam.create_exam_schedule', compact('subjects'));
    }

    /**
     * نستخدم StoreExamScheduleRequest هنا بدلاً من Request العادي
     */
    public function store(StoreExamScheduleRequest $request)
    {
        // البيانات هنا تكون قد مرت بالفعل عبر التحقق
        // نقوم بجلب البيانات المفلترة فقط باستخدام $request->validated()
        ExamSchedule::create($request->validated());

        return redirect()->back()->with('success', 'تم نشر جدول الامتحان بنجاح');
    }

    // صفحة التعديل
    public function edit(ExamSchedule $examSchedule)
    {
        $subjects = Subject::all();
        return view('exam.edit_exam_schedule', compact('examSchedule', 'subjects'));
    }

    // دالة تحديث البيانات
    public function update(StoreExamScheduleRequest $request, ExamSchedule $examSchedule)
    {
        $examSchedule->update($request->validated());
        return redirect()->route('exam.index_exam_schedule')
            ->with('success', 'تم تحديث الجدول بنجاح');
    }

    // دالة الحذف
    public function destroy(ExamSchedule $examSchedule)
    {
        $examSchedule->delete();
        return redirect()
            ->back()
            ->with('success', 'تم حذف الموعد من الجدول بنجاح');
    }
}