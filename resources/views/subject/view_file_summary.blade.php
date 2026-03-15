@extends('layout-cms.main-layout')
@section('title', 'قائمة الملفات المرفوعة')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-4">
                <h4 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-file-alt text-primary mr-2"></i> ملفات المادة: {{ $subjects->name }}
                </h4>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th class="text-center">النوع</th>
                                <th>اسم الملف</th>
                                <th class="text-center">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects->files as $file)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <i class="fas {{ getFileIcon($file->file_path) }} fa-2x"></i>
                                    </td>
                                    <td>{{ $file->file_name }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center flex-wrap" style="gap: 10px;">
                                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                                class="btn btn-sm btn-primary text-white shadow-sm d-flex align-items-center"
                                                title="معاينة الملف">
                                                <i class="fas fa-eye mr-1"></i> <span>معاينة</span>
                                            </a>

                                            <a href="{{ asset('storage/' . $file->file_path) }}" download
                                                class="btn btn-sm btn-success text-white shadow-sm d-flex align-items-center"
                                                title="تحميل الملف">
                                                <i class="fas fa-download mr-1"></i> <span>تحميل</span>
                                            </a>

                                            <button class="btn btn-sm btn-danger shadow-sm d-flex align-items-center"
                                                data-toggle="modal" data-target="#deleteFileModal{{ $file->id }}">
                                                <i class="fas fa-trash-alt mr-1"></i> <span>حذف</span>
                                            </button>
                                        </div>

                                        <div class="modal fade" id="deleteFileModal{{ $file->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                                    <div class="modal-body p-5 text-center">
                                                        <div class="text-danger mb-4">
                                                            <i class="fas fa-exclamation-circle fa-4x"></i>
                                                        </div>
                                                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                                                        <p class="text-muted">هل أنت متأكد من حذف الملف
                                                            <strong>({{ $file->file_name }})</strong>؟<br>
                                                            هذا الإجراء لا يمكن التراجع عنه.
                                                        </p>
                                                        <div class="d-flex justify-content-center mt-4">
                                                            <button type="button" class="btn btn-light px-4 mr-2 rounded-pill"
                                                                data-dismiss="modal">
                                                                إلغاء
                                                            </button>
                                                            <form action=""
                                                                method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-danger px-4 rounded-pill">
                                                                    تأكيد الحذف
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-2"></i>
                                            <p>لا توجد ملفات مرفوعة لهذه المادة حالياً</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @php
        /**
         * دالة مساعدة لتحديد أيقونة الملف بناءً على امتداده
         */
        function getFileIcon($path)
        {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            return match (strtolower($ext)) {
                'pdf' => 'fa-file-pdf text-danger',
                'doc', 'docx' => 'fa-file-word text-primary',
                'jpg', 'png', 'jpeg' => 'fa-file-image text-success',
                default => 'fa-file-alt text-secondary',
            };
        }
    @endphp
@endsection