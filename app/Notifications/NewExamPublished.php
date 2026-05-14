<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewExamPublished extends Notification //implements ShouldQueue
{
   // use Queueable;
    protected $exam;

    /**
     * Create a new notification instance.
     */
    public function __construct($exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'exam_id' => $this->exam->id,
            'title'   => 'اختبار جديد',
            'body'    => 'تم نشر اختبار جديد: ' . $this->exam->subject->name,
            'action_url' => route('exams.student')
        ];
    }
}