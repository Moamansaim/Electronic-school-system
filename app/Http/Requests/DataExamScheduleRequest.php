<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataExamScheduleRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'exam_schedule_id' => 'required|integer|exists:exam_schedules,id',
            'subject_id'     => 'required|exists:subjects,id',
            'exam_date'      => 'required|date|after_or_equal:today',
            'exam_day'       => 'required|string',
            'start_time'     => 'required',
            'end_time'       => 'required|after:start_time',
        ];
    }


    public function attributes(): array
    {
        return [
            'exam_schedule_id' => 'الجدول',
            'subject_id'     => 'المادة الدراسية',
            'exam_date'      => 'التاريخ',
            'exam_day'       => 'اليوم',
            'start_time'     => 'وقت البدء',
            'end_time'       => 'وقت الانتهاء',
        ];
    }

    public function messages(): array
    {
        return [
            // exam_schedule_id
            'exam_schedule_id.required' => 'حقل :attribute مطلوب.',
            'exam_schedule_id.integer'  => 'يجب أن يكون :attribute رقماً صحيحاً.',
            'exam_schedule_id.exists'   => ':attribute الذي اخترته غير موجود.',

            // subject_id
            'subject_id.required' => 'حقل :attribute مطلوب.',
            'subject_id.exists'   => ':attribute المختارة غير موجودة.',

            // exam_date
            'exam_date.required'       => 'حقل :attribute مطلوب.',
            'exam_date.date'           => 'صيغة :attribute غير صحيحة.',
            'exam_date.after_or_equal' => 'يجب أن يكون :attribute مساوياً لليوم أو تاريخاً مستقبلياً.',

            // exam_day
            'exam_day.required' => 'حقل :attribute مطلوب.',
            'exam_day.string'   => 'يجب أن يكون :attribute نصاً.',

            // start_time
            'start_time.required' => 'حقل :attribute مطلوب.',

            // end_time
            'end_time.required' => 'حقل :attribute مطلوب.',
            'end_time.after'    => 'يجب أن يكون :attribute بعد وقت البدء.',
        ];
    }
}