<?php

namespace App\Listeners;

use App\Events\ExamPublished;
use App\Models\User;
use App\Notifications\NewExamPublished;


class SendExamPublishedNotification
{

    /**
     * Handle the event.
     */
    public function handle(ExamPublished $event): void
    {
        $exam = $event->exam->load('subject');

        $classroomIds = $exam->classrooms->pluck('id');   

        User::whereHas('student', function ($query) use ($classroomIds) {

            $query->whereIn('classroom_id', $classroomIds);
        })->chunk(100, function ($students) use ($exam) {

            foreach ($students as $student) {

                $student->notify(
                    new NewExamPublished($exam)
                );
            }
        });
    }
}