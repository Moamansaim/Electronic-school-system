<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\GradeLevelController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('cms')->group(function () {

    Route::resources([
        'grade_levels' => GradeLevelController::class,
        'classrooms' => ClassroomController::class,
        'subjects' => SubjectController::class,
        'teachers' => TeacherController::class,
        'students' => StudentController::class,
    ]);

    Route::resource('class-schedules', ClassScheduleController::class)
        ->except(['create', 'show']);

    Route::get('class-schedules/create/{teacher_id}', [ClassScheduleController::class, 'create'])
        ->name('class-schedules.create');
    Route::get('class-schedules/{teacher_id}', [ClassScheduleController::class, 'show'])
        ->name('class-schedules.show');

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
    Route::prefix('students')
        ->controller(StudentController::class)
        ->as('students.')
        ->group(function () {
            Route::get('grade-levels/{grade_level_id}/data', 'getDataByGrade')
                ->name('get-data-by-grade');
        });
});

require __DIR__.'/auth.php';
