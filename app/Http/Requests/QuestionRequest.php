<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'question_text' => [
                'required',
                'string',
                'min:2',
                'max:300',

            ],
            'question_type' => 'required|in:essay_question,multiple_choice',
            'mark'          => 'required|integer',
            'options'       => 'required_if:question_type,multiple_choice|array',
            'is_correct'    => 'required_if:question_type,multiple_choice',
        ];

        // عند الإنشاء فقط
        if ($this->isMethod('post')) {
            $rules['exam_id'] = 'required|exists:exams,id';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'exam_id' => 'الإختبار',
            'question_text' => 'نص السؤال',
            'question_type' => 'نوع السؤال',
            'mark' => 'علامة السؤال',
            'options' => 'خيارات السؤال',
            'is_correct' => 'الإجابة الصحيحة'
        ];
    }

    public function messages(): array
    {
        return [

            // exam_id
            'exam_id.required' => 'حقل :attribute مطلوب.',
            'exam_id.exists'   => ':attribute المحدد غير موجود في النظام.',

            // question_text
            'question_text.required' => 'حقل :attribute مطلوب.',
            'question_text.string'   => 'يجب أن يكون :attribute نصاً.',
            'question_text.min'      => ':attribute يجب أن لا يقل عن :min أحرف.',
            'question_text.max'      => ':attribute يجب أن لا يتجاوز :max حرفاً.',

            // question_type
            'question_type.required' => 'حقل :attribute مطلوب.',
            'question_type.in'       => ':attribute غير صالح.',

            // mark
            'mark.required' => 'حقل :attribute مطلوب.',
            'mark.integer'  => 'يجب أن تكون :attribute رقماً صحيحاً.',

            // options
            'options.required_if' => 'يجب إدخال :attribute عند اختيار نوع السؤال متعدد الخيارات.',
            'options.array'       => ':attribute يجب أن تكون على شكل قائمة خيارات.',

            // is_correct
            'is_correct.required_if' => 'يجب تحديد :attribute عند اختيار سؤال متعدد الخيارات.',
        ];
    }
}