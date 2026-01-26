<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GradeLevelRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'name' => Str::squish($this->name),
        ]);
    }

    /**
     * الحصول على قواعد التحقق التي تنطبق على الطلب.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:5',
                'max:30',
                Rule::unique('grade_levels', 'name')->ignore($this->route('grade_level')),
                'regex:/^(أولى ثانوي|ثانية ثانوي|ثالثة ثانوي|أولى اعدادي|ثانية اعدادي|ثالثة اعدادي|أولى ابتدائي|ثانية ابتدائي|ثالثة ابتدائي|رابعة ابتدائي|خامسة ابتدائي|سادسة ابتدائي|)$/u',
            ],
        ];
    }

    /**
     * تخصيص أسماء الحقول.
     */
    public function attributes(): array
    {
        return [
            'name' => 'اسم المرحلة الدراسية',
        ];
    }

    /**
     * تخصيص رسائل الخطأ.
     */
    public function messages(): array
    {
        return [
            // الاسم
            'name.required' => 'حقل :attribute مطلوب.',
            'name.string' => 'يجب أن يكون :attribute نصاً.',
            'name.min' => ':attribute يجب أن لا يقل عن :min أحرف.',
            'name.max' => ':attribute يجب أن لا يتجاوز :max حرفاً.',
            'name.unique' => 'قيمة :attribute مستخدمة من قبل.',
            'name.regex' => 'صيغة :attribute غير صحيحة، الصيغ المسموحة هي:    ثالثة ثانوي,ثانية ثانوي،أولى ثانوي،ثالثة اعدادي،ثانية اعدادي،أولى اعدادي،أولى ابتدائي،ثانية ابتدائي،ثالثة ابتدائي،رابعة ابتدائي،خامسة ابتدائي ،سادسة ابتدائي.',
        ];
    }
}
