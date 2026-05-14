<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SummaryFile;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;

class SummaryFileStudentController extends Controller
{

    public function getSubjectsStudent()
    {
        $subjects_student = Student::with(['subjects.files'])
            ->where('user_id', Auth::user()->id)->first();

        return view('subject.view_file_student_summary', compact('subjects_student'));
    }


    public function getSummariesBySubject($subject_id)
    {
        // 1. جلب بيانات الطالب المسجل حالياً والصف التابع له
        $student = Student::where('user_id', Auth::user()->id)->first();
        $class_id = $student->classroom_id;

        // 2. البحث في جدول الآسايمنت (AssignmentTeacher) 
        // لنتحقق من "من هو المعلم" الذي يدرس "هذه المادة" لـ "هذا الصف"
        $assignment = TeacherAssignment::where('subject_id', $subject_id)
            ->where('classroom_id', $class_id)
            ->first();

        if (!$assignment) {
            return back()->with('error', 'لا يوجد معلم معين لهذه المادة في صفك حالياً.');
        }

        // 3. جلب المعلم (User ID) من علاقة المعلم المرتبطة بالآسايمنت
        // نفترض أن جدول الآسايمنت مرتبط بموديل المعلم (Teacher) والمعلم مرتبط باليوزر
        $teacher_user_id = $assignment->teacher->user_id;

        // 4. الآن جلب الملخصات التي تحقق الشرطين:
        // - تابعة للمادة المختارة
        // - المرفوعة من قبل المعلم المحدد (بناءً على user_id)
        $summaries = SummaryFile::where('subject_id', $subject_id)
            ->where('user_id', $teacher_user_id)
            ->get();

     

        return view('subject.summaries_list', compact('summaries'));
    }
}