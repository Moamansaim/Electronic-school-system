<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ClassScheduleRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'day' => Str::squish($this->day),
            'class_schedule' => Str::squish($this->class_schedule),
            'teacher_id' => trim($this->teacher_id),
            'classroom_id' => trim($this->classroom_id),
        ]);
    }

    public function rules(): array
    {
        return [
            'day' => [
                'required',
                'string',
                'min:5',
                'max:40',
            ],
            'class_schedule' => [
                'required',
                'string',
                'min:5',
                'max:40',
            ],
            'teacher_id' => [
                'required',
                'integer',
                'exists:teachers,id',
            ],
            'classroom_id' => [
                'nullable',
                'integer',
                'exists:classrooms,id',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'day' => 'اليوم',
            'class_schedule' => 'الحصة الدراسية',
            'teacher_id' => 'المعلم',
            'classroom_id' => 'الصف الدراسي',
        ];
    }

    public function messages(): array
    {
        return [
            'day.required' => 'حقل :attribute مطلوب.',
            'day.string' => 'يجب أن يكون :attribute نصاً.',
            'day.min' => ':attribute يجب أن لا يقل عن :min أحرف.',
            'day.max' => ':attribute يجب أن لا يتجاوز :max حرفاً.',
            'class_schedule.required' => 'حقل :attribute مطلوب.',
            'class_schedule.string' => 'يجب أن يكون :attribute نصاً.',
            'class_schedule.min' => ':attribute يجب أن لا يقل عن :min أحرف.',
            'class_schedule.max' => ':attribute يجب أن لا يتجاوز :max حرفاً.',
            'teacher_id.required' => 'يجب اختيار :attribute.',
            'teacher_id.exists' => ':attribute المحددة غير موجودة.',
            'teacher_id.integer' => 'عذراً، معرف :attribute يجب أن يكون رقماً صحيحاً.',
            'classroom_id.required' => 'يجب اختيار :attribute.',
            'classroom_id.exists' => ':attribute المحددة غير موجودة.',
            'classroom_id.integer' => 'عذراً، معرف :attribute يجب أن يكون رقماً صحيحاً.',
        ];
    }
}
