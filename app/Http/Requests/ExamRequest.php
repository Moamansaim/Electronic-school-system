<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'teacher_id' => [
                'required',
                'integer',
                'exists:teachers,id',
            ],
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],
            'duration' => [
                'required',
                'integer',
                'min:0',
            ],
            'total_marks' => [
                'required',
                'integer',
                'min:0',
            ],
            'exam_type' => [
                'required',
                'string',
            ],
            'month' => [
                'nullable',
                'string'
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'teacher_id' => 'معرف المعلم',
            'subject_id' => 'المادة الدراسية',
            'duration' => 'مدة الإختبار ',
            'total_marks' => 'درجة الإختبار',
            'month' => 'الشهر',
            'exam_type' => 'نوع الإختبار'
        ];
    }

    public function messages(): array
    {
        return [

            'subject_id.required' => 'يجب اختيار :attribute.',
            'subject_id.exists' => ':attribute المحددة غير موجودة.',
            'subject_id.integer' => 'عذراً، معرف :attribute يجب أن يكون رقماً صحيحاً.',

            'teacher_id.required' => 'يجب اختيار :attribute.',
            'teacher_id.exists' => ':attribute المحدد غير موجود في سجلات المعلمين.',
            'teacher_id.integer' => 'عذراً، معرف :attribute يجب أن يكون رقماً صحيحاً.',

            'duration.required' => 'حقل :attribute مطلوب.',
            'duration.integer' => 'عذراً، معرف :attribute يجب أن يكون رقماً صحيحاً.',
            'duration.min' => ':attribute يجب أن لا يقل عن :min أحرف.',

            'total_marks.required' => 'حقل :attribute مطلوب.',
            'total_marks.integer' => 'عذراً، معرف :attribute يجب أن يكون رقماً صحيحاً.',
            'total_marks.min' => ':attribute يجب أن لا يقل عن :min أحرف.',

            'month.string' => 'يجب أن يكون :attribute نصاً.',

            'exam_type.required' => 'حقل :attribute مطلوب.',
            'exam_type.string' => 'يجب أن يكون :attribute نصاً.',
        ];
    }
}