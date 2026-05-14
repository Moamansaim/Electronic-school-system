<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,excused,late',
            

        ];
    }


    /**
     * تخصيص أسماء الحقول.
     */
    public function attributes(): array
    {
        return [
            'attendance_date' => 'تاريخ التحضير',
            'attendances' => 'قائمة التحضير',
            'attendances.*.student_id' => 'اسم الطالب',
            'attendances.*.status' => 'حالة الحضور',
        ];
    }


    /**
     * تخصيص رسائل الخطأ.
     */
    public function messages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب ولا يمكن تركه فارغاً.',
            'date' => 'حقل :attribute يجب أن يكون تاريخاً صحيحاً.',
            'exists' => 'المحدد في :attribute غير موجود في سجلاتنا.',
            'in' => 'القيمة المختارة في :attribute غير صالحة.',
            'array' => 'يجب إرسال بيانات الحضور كقائمة.',
        ];
    }
}