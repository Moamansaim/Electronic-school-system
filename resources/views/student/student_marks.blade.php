@extends('layout-cms.main-layout')
@section('title', 'نتائج الاختبارات')

@section('content')
    <div class="container-fluid p-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-poll text-primary mr-2"></i> نتائج اختباراتي
                        </h4>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 text-muted py-3">#</th>
                                <th class="border-0 text-muted py-3">اسم الاختبار</th>
                                <th class="border-0 text-muted py-3">نوع الاختبار</th>
                                <th class="border-0 text-muted py-3">الدرجة النهائية</th>
                                <th class="border-0 text-muted py-3">درجة الطالب</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($student_marks as $attempt)
                                <tr>
                                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-soft-primary rounded p-2 mr-3"
                                                style="background-color: #e7f1ff; color: #007bff;">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div>
                                                <span class="font-weight-bold text-dark d-block">
                                                    {{ $attempt->exam->subject->name ?? 'اختبار غير محدد' }}
                                                </span>
                                                {{-- عرض الشهر إذا كان الاختبار شهري --}}
                                                @if($attempt->exam->exam_type == \App\Enums\ExamType::Monthly->value)
                                                    <small class="text-info font-weight-bold">
                                                        <i class="far fa-calendar-alt mr-1"></i> لشهر: {{ $attempt->exam->month }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-light border px-3 py-2">
                                            {{ $attempt->exam->exam_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark font-weight-bold">{{ $attempt->exam->total_marks }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-pill {{ $attempt->final_score >= ($attempt->exam->total_marks / 2) ? 'badge-success' : 'badge-danger' }} px-3 py-2">
                                            {{ $attempt->final_score }}
                                        </span>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-5 text-center">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50">
                                        <p class="text-muted">لا توجد نتائج اختبارات مسجلة حالياً.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($student_marks->hasPages())
                <div class="card-footer bg-white border-top-0 py-4 d-flex justify-content-center">
                    {{ $student_marks->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .table tbody td:nth-child(2) {
            text-align: right;
        }

        .bg-soft-primary {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection