<?php

namespace App\Http\Controllers;

use App\Models\DataExamSchedule;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;


class StudentExamScheduleController extends Controller
{
    public function getExamScheduleForStudent()
    {
        // 1. جلب معرفات المواد المسندة للطالب الحالي فقط
        $studentSubjectIds = Student::where('user_id', Auth::id())
            ->first()
            ->subjects()
            ->pluck('subjects.id'); // جلب قائمة بـ IDs المواد

        // 2. جلب جدول الاختبارات للمواد التي يدرسها الطالب فقط
        $examSchedule = DataExamSchedule::with(['subject' , 'examSchedule'])
            ->whereIn('subject_id', $studentSubjectIds)
            ->get();

        return view('student.schdule_exam', compact('examSchedule'));
    }
    
}