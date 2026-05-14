<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataExamScheduleRequest;
use App\Models\DataExamSchedule;
use App\Models\Subject;

class DataExamScheduleController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataExamScheduleRequest $request)
    {
        DataExamSchedule::create($request->validated());
        return redirect()
            ->back()
            ->with('success', 'تمت إضافة الموعد بنجاح');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataExamSchedule $dataExamSchedule)
    {
        $subjects = Subject::select('id', 'name')->get();
        return view('exam.edit_exam_schedule', compact('dataExamSchedule', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DataExamScheduleRequest $request, DataExamSchedule $dataExamSchedule)
    {
        $dataExamSchedule->update($request->validated());
        return redirect()
            ->route('exam-schedules.index')
            ->with('success', 'تم تحديث الموعد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataExamSchedule $dataExamSchedule)
    {
        $dataExamSchedule->delete();
        return redirect()
            ->back()
            ->with('success', 'تم حذف  الموعد   بنجاح');
    }
}