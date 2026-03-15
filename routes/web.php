<?php

use App\Enums\ExamType;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeLevelController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SummaryFileController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('cms')->group(function () {

    Route::resources([
        'grade_levels' => GradeLevelController::class,
        'classrooms' => ClassroomController::class,
        'subjects' => SubjectController::class,
        'teachers' => TeacherController::class,
        'students' => StudentController::class,
        'exams' => ExamController::class,
        'questions' => QuestionController::class,
    ]);

    //class-schedules
    Route::resource('class-schedules', ClassScheduleController::class)
        ->except(['create', 'show', 'edit']);
    Route::get('class-schedules/create/{teacher_id}', [ClassScheduleController::class, 'create'])
        ->name('class-schedules.create');
    Route::get('class-schedules/{teacher_id}', [ClassScheduleController::class, 'show'])
        ->name('class-schedules.show');
    Route::get('class-schedules/{class_schedule}/{teacher_id}/edit', [ClassScheduleController::class, 'edit'])
        ->name('class-schedules.edit');

    //teachers
    Route::prefix('teachers')
        ->controller(TeacherController::class)
        ->as('teachers.')
        ->group(function () {

            Route::get('{teacher_id}/assignment', 'teacherAssignment')
                ->name('assignment');
            Route::get('{teacher_id}/assignments-list', 'dataTeacherAssignment')
                ->name('assignments.data');
            Route::get('grade-levels/{grade_level_id}/data', 'getDataByGrade')
                ->name('get-data-by-grade');
            Route::post('assignments', 'storeAssignment')
                ->name('storeAssignment');
            Route::delete('{teacher_assignment_id}/assignment', 'destroyTeacherAssignment')
                ->name('destroyAssignment');
        });

    Route::get('exam/{id}/questions', [ExamController::class,  'exam_questions'])
        ->name('exam.questions');


    Route::post('subject/files/store', [SummaryFileController::class, 'store'])->name('files.store');
    Route::get('subject/files/view/{id}', [SummaryFileController::class, 'viewFileSummary'])->name('files.view');

    //students
    Route::prefix('students')
        ->controller(StudentController::class)
        ->as('students.')
        ->group(function () {
            Route::get('grade-levels/{grade_level_id}/data', 'getDataByGrade')
                ->name('get-data-by-grade');
        });
});

require __DIR__ . '/auth.php';