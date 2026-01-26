<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClassroomRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => Str::squish($this->name),
            'grade_level_id' => trim($this->grade_level_id),
            'teacher_id' => trim($this->teacher_id),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:5',
                'max:40',
                Rule::unique('classrooms', 'name')->ignore($this->route('classroom')),
                'regex:/^الصف\s[\p{L}\p{N}\s]+$/u',
            ],
            'grade_level_id' => [
                'required',
                'integer',
                'exists:grade_levels,id',
            ],
            'teacher_id' => [
                'nullable',
                'integer',
                'unique:classrooms,teacher_id',
                'exists:teachers,id',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم الصف الدراسي',
            'grade_level_id' => 'المرحلة الدراسية',
            'teacher_id' => 'مربي الفصل',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'حقل :attribute مطلوب.',
            'name.string' => 'يجب أن يكون :attribute نصاً.',
            'name.min' => ':attribute يجب أن لا يقل عن :min أحرف.',
            'name.max' => ':attribute يجب أن لا يتجاوز :max حرفاً.',
            'name.unique' => 'قيمة :attribute مستخدمة من قبل.',
            'name.regex' => 'صيغة :attribute غير صحيحة (مثال: الصف الأول).',
            'grade_level_id.required' => 'يجب اختيار :attribute.',
            'grade_level_id.exists' => ':attribute المحددة غير موجودة.',
            'grade_level_id.integer' => 'عذراً، معرف :attribute يجب أن يكون رقماً صحيحاً.',
            'teacher_id.exists' => ':attribute المحدد غير موجود في سجلات المعلمين.',
            'teacher_id.unique' => 'هذا المعلم هو بالفعل :attribute لصف آخر، لا يمكن تعيينه لأكثر من صف.',
            'teacher_id.integer' => 'عذراً، معرف :attribute يجب أن يكون رقماً صحيحاً.',
        ];
    }
}
