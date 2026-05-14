<?php

namespace App\Http\Requests;

use App\Models\ExamSchedule;
use App\Rules\MaxOneRecord;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamScheduleRequest extends FormRequest
{


    /**
     * قواعد التحقق التي تنطبق على الطلب.
     */
    public function rules(): array
    {
        $param = $this->route('exam_schedule');
        $id = ($param instanceof ExamSchedule ? $param->id : $param);
        return [
            'schedule_title' => [
                'required',
                'string',
                'max:255',
                new MaxOneRecord($id)
            ]
        ];
    }


    public function attributes(): array
    {
        return [
            'schedule_title' => 'عنوان الجدول',
        ];
    }

    /**
     * تخصيص رسائل الخطأ بالعربية.
     */
    public function messages(): array
    {
        return [
            'schedule_title.required' => 'حقل :attribute مطلوب.',
            'schedule_title.string' => 'يجب أن يكون :attribute نصاً.',
            'schedule_title.max' => ':attribute يجب أن لا يتجاوز :max حرفاً.',
        ];
    }
}