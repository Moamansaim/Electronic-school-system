<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamScheduleRequest extends FormRequest
{
   

    /**
     * قواعد التحقق التي تنطبق على الطلب.
     */
    public function rules(): array
    {
        return [
            'schedule_title' => 'required|string|max:255',
            'subject_id'     => 'required|exists:subjects,id',
            'exam_date'      => 'required|date|after_or_equal:today',
            'exam_day'       => 'required|string',
            'start_time'     => 'required',
            'end_time'       => 'required|after:start_time',
            'exam_type'      => 'required|in:midterm,final',
        ];
    }

    /**
     * تخصيص رسائل الخطأ بالعربية.
     */
    public function messages(): array
    {
        return [
            'schedule_title.required' => 'يجب إدخال عنوان للجدول.',
            'subject_id.required'     => 'يرجى اختيار المادة الدراسية.',
            'subject_id.exists'       => 'المادة المختارة غير موجودة.',
            'exam_date.required'      => 'تاريخ الامتحان مطلوب.',
            'exam_date.after_or_equal' => 'لا يمكن جدولة امتحان في تاريخ قديم.',
            'end_time.after'          => 'وقت الانتهاء يجب أن يكون بعد وقت البدء.',
            'exam_type.in'            => 'نوع الامتحان يجب أن يكون نصفي أو نهائي.',
        ];
    }
}