<?php

namespace App\Http\Requests;

use App\Rules\SubjectWord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'name' => Str::squish($this->name),
            'grade_level_id' => trim($this->grade_level_id),
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
                Rule::unique('subjects', column: 'name')->ignore($this->route('subject')),
                /**
                 * شرح الريجكس المطور:
                 * ^مادة\s : يبدأ بكلمة مادة متبوعة بمسافة
                 * [\p{L}\p{N}\s]+ : يسمح بأي أحرف أو أرقام (اسم المادة) ويمنع الرموز
                 * \s-\s : يفرض وجود مسافة ثم شرطة ثم مسافة
                 * (الثانوية|الاعدادية|الابتدائية)$ : ينتهي بإحدى المراحل الثلاث حصراً
                 */
                'regex:/^مادة\s[\p{L}\p{N}\s]+\s-\s[\p{L}\s]+$/u',
                new SubjectWord($this->grade_level_id),
            ],
            'grade_level_id' => ['required', 'integer', 'exists:grade_levels,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'اسم المادة الدراسية',
            'grade_level_id' => 'المرحلة الدراسية',
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
            'name.regex' => 'صيغة :attribute غير صحيحة، يجب أن تكون بالشكل: مادة [الاسم] - [المرحلة]، وبدون رموز خاصة.',
            'grade_level_id.required' => 'يجب اختيار :attribute.',
            'grade_level_id.exists' => ':attribute المحددة غير موجودة.',
            'grade_level_id.integer' => 'عذراً، معرف :attribute يجب أن يكون رقماً صحيحاً.',
        ];
    }
}
