<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SummaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SummaryFileController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'files'      => 'required|array',
            'files.*'    => 'file|mimes:pdf,jpg,png,docx|max:10240',
        ], [
            'user_id.required'    => 'معرف المستخدم مطلوب.',
            'user_id.exists'      => 'المستخدم غير موجود.',
            'subject_id.required' => 'يجب اختيار المادة الدراسية.',
            'subject_id.exists'   => 'المادة المختارة غير موجودة.',
            'files.required'      => 'يرجى اختيار ملفات للرفع.',
            'files.array'         => 'صيغة البيانات المرسلة غير صحيحة.',
            'files.*.mimes'       => 'الملفات المسموح بها هي: PDF, JPG, PNG فقط.',
            'files.*.max'         => 'حجم الملف يجب ألا يتجاوز 10 ميجابايت.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors'  => $validator->errors() //   
            ], 422);
        }

        try {


            foreach ($request->file('files') as $file) {
                $path = $file->store('summaries', 'public');

                $savedFile = SummaryFile::create([
                    'user_id'    => $request->user_id,
                    'subject_id' => $request->subject_id,
                    'file_path'  => $path,
                    'file_name'  => $file->getClientOriginalName(),
                ]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'تم رفع الملفات بنجاح',

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'حدث خطأ غير متوقع أثناء الرفع: '
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function viewFileSummary($id): View
    {
        $subjects = Subject::with('files')->findOrFail($id);
        return view('subject.view_file_summary', compact('subjects'));
    }
}