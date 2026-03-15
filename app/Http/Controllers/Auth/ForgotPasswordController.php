<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    // 1. التحقق من الهوية وإرجاع تلميح الهاتف
    public function verifyIdentity(Request $request)
    {
        // التحقق من الحقول المطلوبة
        $validator = Validator::make($request->all(), [
            'school_id'   => 'required|string',
            'national_id' => 'required|string',
        ], [
            // الرسائل المخصصة لكل حقل ولكل قاعدة (Rule)
            'school_id.required'   => 'يرجى إدخال الرقم المدرسي.',
            'school_id.string'     => 'الرقم المدرسي يجب أن يكون نصاً.',

            'national_id.required' => 'يرجى إدخال رقم الهوية.',
            'national_id.string'   => 'رقم الهوية يجب أن يكون نصاً.',
        ]);

        // إذا فشل التحقق، قم بإرجاع الخطأ الأول
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        // البحث عن المستخدم الذي يملك الرقم الجامعي والهوية
        $user = User::where('school_id', $request->school_id)
            ->where(function ($query) use ($request) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('national_id', $request->national_id);
                })->orWhereHas('teacher', function ($q) use ($request) {
                    $q->where('national_id', $request->national_id);
                });
            })
            ->with(['student.phones', 'teacher.phones'])
            ->first();

        if (!$user) {
            return response()->json(['message' => 'بيانات الهوية أو الرقم الجامعي غير صحيحة.'], 404);
        }

        // جلب أول رقم هاتف موجود
        $phoneModel = $user->student?->phones->first() ?? $user->teacher?->phones->first();

        if (!$phoneModel) {
            return response()->json([
                'message' => 'لا يوجد رقم هاتف مسجل لهذا المستخدم.'
            ], 404);
        }

        $phone = $phoneModel->phone_number;

        return response()->json([
            'phone_hint' => substr($phone, 0, 3) . '****' . substr($phone, -2)
        ]);
    }

    // 2. التحقق من الهاتف، تغيير الباسورد، والدخول التلقائي
    public function resetPassword(Request $request)
    {
        // التحقق من صحة الحقول
        $validator = Validator::make($request->all(), [
            'school_id'    => 'required|string',
            'full_phone'   => 'required|string',
            'password'     => 'required|string|min:8|confirmed',
        ], [
            // رسائل حقل الرقم المدرسي
            'school_id.required'   => 'يجب إدخال الرقم المدرسي.',

            // رسائل حقل الهاتف
            'full_phone.required'  => 'يجب إدخال رقم الهاتف.',

            // رسائل حقل كلمة المرور
            'password.required'    => 'يجب إدخال كلمة المرور.',
            'password.min'         => 'يجب ألا تقل كلمة المرور عن 8 خانات.',
            'password.confirmed'   => 'كلمة المرور وتأكيدها غير متطابقين.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        // البحث عن المستخدم الذي يملك هذا الهاتف ضمن علاقة الهواتف
        $user = User::where('school_id', $request->school_id)
            ->where(function ($query) use ($request) {
                $query->whereHas('student.phones', fn($q) => $q->where('phone_number', $request->full_phone))
                    ->orWhereHas('teacher.phones', fn($q) => $q->where('phone_number', $request->full_phone));
            })->first();

        if (!$user) {
            return response()->json(['message' => 'رقم الهاتف غير مطابق للأرقام المسجلة.'], 422);
        }

        // تحديث كلمة المرور بشكل آمن
        $user->password = Hash::make($request->password);
        $user->save();

        // تسجيل الدخول تلقائياً
        Auth::login($user);

        return response()->json([
            'redirect' => route('grade_levels.index'),
            'message' => 'تم تغيير كلمة المرور وتسجيل الدخول بنجاح'
        ]);
    }
}