<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
{
    /**
     * تجهيز البيانات قبل عملية التحقق (تنظيف المسافات)
     */
    protected function prepareForValidation()
    {

        if ($this->has('phone_numbers')) {

            $cleaned_phones = array_map(function ($item) {
                return str_replace(' ', '', $item);
            }, $this->phone_numbers);

            $this->merge([
                'phone_numers' => $cleaned_phones,
            ]);
        }

        $this->merge([
            'first_name' => Str::squish($this->first_name),
            'father_name' => Str::squish($this->father_name),
            'grandfather_name' => Str::squish($this->grandfather_name),
            'family_name' => Str::squish($this->family_name),
            'city' => Str::squish($this->city),
            'district' => Str::squish($this->district),
            'street' => Str::squish($this->street),
            'national_id' => str_replace(' ', '', $this->national_id),
            'date_of_birth' => trim($this->date_of_birth),
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:30', 'min:3'],
            'father_name' => ['required', 'string', 'max:30', 'min:3'],
            'grandfather_name' => ['required', 'string', 'max:30', 'min:3'],
            'family_name' => ['required', 'string', 'max:30', 'min:3'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'national_id' => [
                'required',
                'numeric',
                'digits:9',
                Rule::unique('teachers', 'national_id')->ignore($this->route('teacher')),
            ],
            'city' => ['nullable', 'string', 'max:50'],
            'district' => ['nullable', 'string', 'max:50'],
            'street' => ['nullable', 'string', 'max:100'],
            'phone_numbers' => [
                'required',
                'array',
                'min:1',

            ],
            'phone_numbers.*' => [
                'required',
                'numeric',
                'digits:10',
                'distinct',
                Rule::unique('teacher_phonenumbers', 'phone_number')
                    ->whereNot('teacher_id', $this->route('teacher')),

            ],

        ];
    }

    /**
     * ملاحظة: دالة attributes لم تعد ضرورية جداً إذا قمت بتخصيص كل الرسائل يدوياً،
     * لكن يفضل تركها كإجراء احتياطي لأي قاعدة قد تنساها.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'اسم المعلم',
            'father_name' => 'اسم الأب',
            'grandfather_name' => 'اسم الجد',
            'family_name' => 'اسم العائلة',
            'date_of_birth' => 'تاريخ الميلاد',
            'national_id' => 'رقم الهوية',
            'city' => 'المدينة',
            'district' => 'الحي',
            'street' => 'الشارع',
            'phone_numbers' => 'أرقام الجوال',
            'phone_numbers.*' => 'رقم الجوال',
            'password' => 'كلمة المرور',
        ];
    }

    /**
     * تخصيص رسائل الخطأ لكل حقل على حدة
     */
    public function messages(): array
    {
        return [
            // الاسم الأول
            'first_name.required' => 'حقل اسم المعلم مطلوب.',
            'first_name.string' => 'اسم المعلم يجب أن يكون نصاً.',
            'first_name.min' => 'اسم المعلم يجب أن لا يقل عن :min أحرف.',
            'first_name.max' => 'اسم المعلم يجب أن لا يتجاوز :max حرفاً.',

            // اسم الأب
            'father_name.required' => 'حقل اسم الأب مطلوب.',
            'father_name.string' => 'اسم الأب يجب أن يكون نصاً.',
            'father_name.min' => 'اسم الأب يجب أن لا يقل عن :min أحرف.',
            'father_name.max' => 'اسم الأب يجب أن لا يتجاوز :max حرفاً.',

            // اسم الجد
            'grandfather_name.required' => 'حقل اسم الجد مطلوب.',
            'grandfather_name.string' => 'اسم الجد يجب أن يكون نصاً.',
            'grandfather_name.min' => 'اسم الجد يجب أن لا يقل عن :min أحرف.',
            'grandfather_name.max' => 'اسم الجد يجب أن لا يتجاوز :max حرفاً.',

            // اسم العائلة
            'family_name.required' => 'حقل اسم العائلة مطلوب.',
            'family_name.string' => 'اسم العائلة يجب أن يكون نصاً.',
            'family_name.min' => 'اسم العائلة يجب أن لا يقل عن :min أحرف.',
            'family_name.max' => 'اسم العائلة يجب أن لا يتجاوز :max حرفاً.',

            // تاريخ الميلاد
            'date_of_birth.required' => 'حقل تاريخ الميلاد مطلوب.',
            'date_of_birth.date' => 'تاريخ الميلاد يجب أن يكون تاريخاً صالحاً.',
            'date_of_birth.before' => 'تاريخ الميلاد يجب أن يكون قبل تاريخ اليوم.',

            // رقم الهوية
            'national_id.required' => 'حقل رقم الهوية مطلوب.',
            'national_id.numeric' => 'رقم الهوية يجب أن يحتوي على أرقام فقط.',
            'national_id.digits' => 'رقم الهوية يجب أن يتكون من :digits أرقام.',
            'national_id.unique' => 'رقم الهوية مستخدم مسبقاً لمعلم آخر.',

            // العنوان
            'city.string' => 'المدينة يجب أن تكون نصاً.',
            'city.max' => 'اسم المدينة يجب أن لا يتجاوز :max حرفاً.',

            'district.string' => 'الحي يجب أن يكون نصاً.',
            'district.max' => 'اسم الحي يجب أن لا يتجاوز :max حرفاً.',

            'street.string' => 'الشارع يجب أن يكون نصاً.',
            'street.max' => 'اسم الشارع يجب أن لا يتجاوز :max حرفاً.',

            // أرقام الجوال
            'phone_numbers.required' => 'يجب إدخال رقم جوال واحد على الأقل.',
            'phone_numbers.array' => 'تنسيق أرقام الجوال غير صالح.',
            'phone_numbers.min' => 'يجب إدخال رقم جوال واحد على الأقل.',

            'phone_numbers.*.required' => 'حقل رقم الجوال مطلوب.',
            'phone_numbers.*.numeric' => 'رقم الجوال يجب أن يحتوي على أرقام فقط.',
            'phone_numbers.*.digits' => 'رقم الجوال يجب أن يتكون من :digits أرقام.',
            'phone_numbers.*.distinct' => 'لا يجوز تكرار رقم الجوال.',
            'phone_numbers.*.unique' => 'رقم الجوال مستخدم مسبقاً.',
        ];
    }
}
