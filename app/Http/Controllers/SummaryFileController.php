<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SummaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SummaryFileController extends Controller
{
    /**
     * دالة رفع الملفات: تقوم باستقبال الملفات من الطلب والتحقق منها ثم تخزينها 
     * في مجلد الـ storage وحفظ مسارها في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المرسلة (المدخلات والملفات)
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'files'      => 'required|array',
            'files.*'    => 'file|mimes:pdf,jpg,png|max:10240', //حجم أقصى 10 ميجا بايت
        ], [
            // رسائل الخطأ المخصصة بالعربية
            'user_id.required'    => 'معرف المستخدم مطلوب.',
            'user_id.exists'      => 'المستخدم غير موجود.',
            'subject_id.required' => 'يجب اختيار المادة الدراسية.',
            'subject_id.exists'   => 'المادة المختارة غير موجودة.',
            'files.required'      => 'يرجى اختيار ملفات للرفع.',
            'files.array'         => 'صيغة البيانات المرسلة غير صحيحة.',
            'files.*.mimes'       => 'الملفات المسموح بها هي: PDF, JPG, PNG فقط.',
            'files.*.max'         => 'حجم الملف يجب ألا يتجاوز 10 ميجابايت.',
        ]);

        // في حال فشل التحقق، نقوم بإرجاع رسالة خطأ مع رموز الحالة 422
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // معالجة الملفات المرفوعة وتخزينها
            foreach ($request->file('files') as $file) {
                // تخزين الملف في مجلد 'summaries' داخل قرص 'public'
                $path = $file->store('summaries', 'public');

                // حفظ بيانات الملف في قاعدة البيانات
                $savedFile = SummaryFile::create([
                    'summary_content_file' => $request->summary_content_file,
                    'user_id'    => $request->user_id,
                    'subject_id' => $request->subject_id,
                    'file_path'  => $path, // المسار الناتج من التخزين
                    'file_name'  => $file->getClientOriginalName(), // الاسم الأصلي للملف
                ]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'تم رفع الملفات بنجاح',
            ], 200);
        } catch (\Exception $e) {
            // في حال حدوث أي خطأ برمجي غير متوقع
            return response()->json([
                'status'  => false,
                'message' => 'حدث خطأ غير متوقع أثناء الرفع',
            ], 500);
        }
    }


    /**
     * دالة عرض صفحة ملفات المادة: تقوم بجلب بيانات المادة المحددة 
     * مع كافة ملفاتها المرتبطة بها وعرضها في واجهة المستخدم.
     */
    public function viewFileSummary($id): View
    {
        // جلب المادة مع علاقتها بالملفات باستخدام Eager Loading (with)
        $subjects = Subject::with('files')->findOrFail($id);
        return view('subject.view_file_summary', compact('subjects'));
    }


    /**
     * دالة حذف الملف: تقوم بحذف الملف فيزيائياً من مجلد التخزين (Storage) 
     * ومن ثم حذف السجل الخاص به من قاعدة البيانات.
     */
    public function destroy($id)
    {
        // 1. جلب السجل
        $file = SummaryFile::findOrFail($id);

        // 2. المسار المخزن في قاعدة البيانات هو مثلاً: "summaries/filename.pdf"
        $path = $file->file_path;

        // 3. الحذف باستخدام قرص 'public' مباشرة
        // Storage::disk('public') يوجهنا تلقائياً إلى مجلد storage/app/public
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // 4. حذف السجل من قاعدة البيانات
        $file->delete();

        return redirect()->back()->with('success', 'تم حذف الملف بنجاح.');
    }
}