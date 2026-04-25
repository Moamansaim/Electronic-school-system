<?php


use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\ExamClassroomController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\ExamStudentController;
use App\Http\Controllers\GradeLevelController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SummaryFileController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// جميع مسارات لوحة التحكم (CMS) محمية بـ Middleware للتحقق من تسجيل الدخول
Route::middleware('auth')->prefix('cms')->group(function () {



    // الموارد الأساسية (Resources) التي تعتمد على عمليات CRUD القياسية
    Route::resources([
        'grade_levels' => GradeLevelController::class,
        'classrooms'   => ClassroomController::class,
        'subjects'     => SubjectController::class,
        'teachers'     => TeacherController::class,
        'students'     => StudentController::class,
        'exams'        => ExamController::class,
        'questions'    => QuestionController::class,
        'exam-schedules' => ExamScheduleController::class
    ]);

    // مسارات إدارة الجداول الدراسية (Class Schedules)
    Route::resource('class-schedules', ClassScheduleController::class)
        ->except(['create', 'show', 'edit']); // استثناء العمليات التي تتطلب بارامترات مخصصة
    Route::resource('questions', QuestionController::class)
        ->except(['edit']);

    Route::get('questions/{question}/{exam_id}/edit', [QuestionController::class, 'edit'])
        ->name('questions.edit');

    Route::get('class-schedules/create/{teacher_id}', [ClassScheduleController::class, 'create'])
        ->name('class-schedules.create');
    Route::get('class-schedules/{teacher_id}', [ClassScheduleController::class, 'show'])
        ->name('class-schedules.show');
    Route::get('class-schedules/{class_schedule}/{teacher_id}/edit', [ClassScheduleController::class, 'edit'])
        ->name('class-schedules.edit');

    // مسارات إدارة توزيعات المعلمين (Teacher Assignments)
    Route::prefix('teachers')
        ->controller(TeacherController::class)
        ->as('teachers.')
        ->group(function () {
            Route::get('{teacher_id}/assignment', 'teacherAssignment')->name('assignment');
            Route::get('{teacher_id}/assignments-list', 'dataTeacherAssignment')->name('assignments.data');
            Route::get('grade-levels/{grade_level_id}/data', 'getDataByGrade')->name('get-data-by-grade');
            Route::post('assignments', 'storeAssignment')->name('storeAssignment');
            Route::delete('{teacher_assignment_id}/assignment', 'destroyTeacherAssignment')->name('destroyAssignment');
        });


    // مسار خاص لجلب الأسئلة المرتبطة باختبار محدد
    Route::get('exam/{id}/questions', [ExamController::class, 'exam_questions'])
        ->name('exam.questions');
    //مسار  اختبارات الطلاب

    //مسار  بدأ جلسة الاختبار الطلاب
    Route::post('exam/start/students/{id}', [ExamStudentController::class, 'startExamStudent'])
        ->name('exams.start');

    //مسار الحفظ التلقائي للأسئلة
    Route::post('exam/auto-save/answers/students', [ExamStudentController::class, 'autoSaveAnswer'])
        ->name('auto_save.answers');

    //مسار تسليم الاختبار من قبل الطلاب
    Route::post('exam/submit-exam', [ExamStudentController::class, 'submitExam'])
        ->name('exams.submit_exam');

    //مسار الاختبارات الطلابية
    Route::get('exam/students', [ExamStudentController::class, 'viewExamStudent'])
        ->name('exams.student');

    //مسار رؤية الصفوف المنشورة لاختبار معين
    Route::get('exam/show/classrooms/{id}', [ExamClassroomController::class, 'showExamClassrooms'])
        ->name('exams.show.classroom');

    //مسار الذهاب  لصفحة تتبع حالات الطلاب في الاختبارات
    Route::get('exam/monitor/students/{exam_id}/{classroom_id}', [ExamClassroomController::class, 'monitorExamStudent'])
        ->name('exams.teacher.exams.monitor');

    //مسار الذهاب  لصفحة تتبع حالات الطلاب في الاختبارات
    Route::get('exam/student/marks', [ExamStudentController::class, 'studentExamMarks'])
        ->name('exams.student.marks');

        //مسار تعديل علامة الطالب
    Route::post('/update-exam-score', [ExamClassroomController::class, 'updateScore'])
        ->name('exam.update-score');


    //viewExamStudent
    // مسارات إدارة الملفات والمُلخصات الدراسية (Summary Files)
    Route::post('subject/files/store', [SummaryFileController::class, 'store'])->name('files.store');
    Route::get('subject/files/view/{id}', [SummaryFileController::class, 'viewFileSummary'])->name('files.view');
    Route::delete('files/destroy/{id}', [SummaryFileController::class, 'destroy'])->name('files.destroy');

    // مسارات خاصة بإدارة الطلاب
    Route::prefix('students')
        ->controller(StudentController::class)
        ->as('students.')
        ->group(function () {
            Route::get('grade-levels/{grade_level_id}/data', 'getDataByGrade')
                ->name('get-data-by-grade');
            Route::get('show/student-schedule', 'showStudentSchedule')->name('show-schedule');
        });
    //مسار نشر الاختبارات الطلابية
    Route::post('exams/publish', [ExamClassroomController::class, 'store'])->name('exams.publish.store');

    // مسار إيقاف نشر الاختبارات
    Route::delete('exams/unpublish/destroy/{id}', [ExamClassroomController::class, 'destroy'])
        ->name('exams.publish.destroy');
});


// تضمين مسارات المصادقة (تسجيل الدخول والخروج)
require __DIR__ . '/auth.php';