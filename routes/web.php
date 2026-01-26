<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\GradeLevelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout-cms.main-layout');
});

// Route::prefix('cms')
//     ->controller(GradeLevelController::class)
//     ->as('grade_levels.')
//     ->group(function () {
//         Route::resource('/', GradeLevelController::class);
//     });

Route::prefix('cms')->group(function () {

    Route::resources([
        'grade_levels' => GradeLevelController::class,
        'classrooms' => ClassroomController::class,
        'subjects' => SubjectController::class,
        'teachers' => TeacherController::class,
        'students' => StudentController::class,
    ]);

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
