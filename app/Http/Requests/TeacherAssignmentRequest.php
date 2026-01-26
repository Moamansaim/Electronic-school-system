<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherAssignmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'grade_level_id' => ['required', 'integer', 'exists:grade_levels,id'],
            'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'teacher_id' => 'اسم المعلم ',
            'grade_level_id' => 'المرحلة الدراسية',
            'classroom_id' => ' الصف الدراسي',
            'subject_id' => ' المادة الدراسية',
        ];
    }

    public function messages(): array
    {
        return [
            'teacher_id.required' => 'حقل :attribute مطلوب.',
            'teacher_id.integer' => 'قيمة حقل :attribute غير صالحة.',
            'teacher_id.exists' => ':attribute المحدد غير موجود.',

            'grade_level_id.required' => 'حقل :attribute مطلوب.',
            'grade_level_id.integer' => 'قيمة حقل :attribute غير صالحة.',
            'grade_level_id.exists' => ':attribute المحددة غير موجودة.',

            'classroom_id.required' => 'حقل :attribute مطلوب.',
            'classroom_id.integer' => 'قيمة حقل :attribute غير صالحة.',
            'classroom_id.exists' => ':attribute المحدد غير موجود.',

            'subject_id.required' => 'حقل :attribute مطلوب.',
            'subject_id.integer' => 'قيمة حقل :attribute غير صالحة.',
            'subject_id.exists' => ':attribute المحددة غير موجودة.',
        ];
    }
}
