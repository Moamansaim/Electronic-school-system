<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Support\Facades\Request;


class AttendanceController extends Controller
{
    // عرض الصفوف (البطاقات)
    public function index()
    {
        $classrooms = Classroom::all(); // افترضنا وجود مودل للفصول
        return view('attendance.classroom', compact('classrooms'));
    }

    // جلب طلاب صف معين
    public function showClassStudents($class_id)
    {
        $students = Student::where('classroom_id', $class_id)->get();
        return view('attendance.attendance_index', compact('students', 'class_id'));
    }

    // حفظ الحضور الجماعي
    public function store(AttendanceRequest $request)
    {
        foreach ($request->attendances as $record) {
            Attendance::updateOrCreate([

                'student_id' => $record['student_id'],
                'attendance_date' => $request->attendance_date,

            ], [
                'status' => $record['status'],
            ]);
        }
        return redirect()->back()->with('success', 'تم تسجيل الحضور بنجاح');
    }

    // سجل حضور طالب محدد للتعديل
    public function studentLog($student_id)
    {
        $student = Student::findOrFail($student_id);
        $logs = Attendance::where('student_id', $student_id)->orderBy('attendance_date', 'desc')->get();
        return view('attendance.attendance_edit', compact('student', 'logs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,excused,late',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update(['status' => $request->post('status')]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الحضور بنجاح'
        ]);
    }

    // حذف سجل حضور
    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'تم حذف السجل بنجاح');
    }
}