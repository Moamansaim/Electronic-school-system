@extends('layout-cms.main-layout')
@section('title', 'قائمة الإختبارات ')

@section('content')
    <div class="" dir="rtl">
        <x-grade-level-success-component />
        <x-grade-level-error-component />

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col-xl-3 col-lg-4">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-graduation-cap text-primary ml-2"></i> قائمة الإختبارات
                        </h4>
                    </div>
                    <div class="col-xl-9 col-lg-8 text-left">
                        <div class="d-flex flex-wrap justify-content-end align-items-center" style="gap: 10px;">
                            <form action="{{ route('exams.index') }}" method="GET" class="ml-2">
                                <div class="input-group border rounded-pill px-2 py-1 bg-light shadow-sm">
                                    <input type="text" name="search" class="form-control bg-transparent border-0"
                                        placeholder="ابحث عن إختبار..." value="{{ request('search') }}"
                                        style="width: 180px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-link text-muted p-0 px-2" type="submit"><i
                                                class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                            <a href="{{ route('exams.create') }}"
                                class="btn btn-primary shadow-sm px-4 rounded-pill font-weight-bold">
                                <i class="fas fa-plus ml-1"></i> إنشاء إختبار
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>الإختبار</th>
                                <th>مدة الإختبار</th>
                                <th>نوع الإختبار</th>
                                <th>الشهر</th>
                                <th>العلامة</th>
                                <th>المعلم</th>
                                <th>تاريخ الإنشاء</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($exams as $exam)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $exam->subject->name }}</td>
                                    <td>{{ $exam->duration }} دقيقة</td>
                                    <td>{{ $exam->exam_type }}</td>
                                    <td>{{ $exam->month }}</td>
                                    <td>{{ $exam->total_marks }}</td>
                                    <td>{{ $exam->teacher->full_name }}</td>
                                    <td><small>{{ $exam->created_at->format('Y-m-d') }}</small></td>
                                    <td class="text-center" style="overflow: visible;">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-pill shadow-sm px-3" type="button"
                                                id="dropdownMenuButton{{ $exam->id }}" data-toggle="dropdown"
                                                data-boundary="window" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v text-muted"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right border-0 shadow-lg mt-2"
                                                aria-labelledby="dropdownMenuButton{{ $exam->id }}"
                                                style="border-radius: 12px; min-width: 180px; z-index: 1050; text-align: right;">

                                                <h6 class="dropdown-header text-xs text-uppercase text-muted font-weight-bold">
                                                    خيارات الاختبار
                                                </h6>

                                                <a class="dropdown-item" href="{{ route('exams.edit', $exam->id) }}">
                                                    <i class="fas fa-pen text-info mr-2"></i> تعديل
                                                </a>

                                                <a class="dropdown-item" href="{{ route('exam.questions', $exam->id) }}">
                                                    <i class="fas fa-eye text-primary mr-2"></i> عرض الأسئلة
                                                </a>

                                                <a class="dropdown-item" href="{{ route('exams.show', $exam->id) }}">
                                                    <i class="fas fa-plus-circle text-dark mr-2"></i> إضافة سؤال
                                                </a>

                                                <div class="dropdown-divider"></div>

                                                <button type="button" class="dropdown-item text-danger" data-toggle="modal"
                                                    data-target="#deleteModal{{ $exam->id }}">
                                                    <i class="fas fa-trash-alt mr-2"></i> حذف الاختبار
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">لا توجد نتائج</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- مودلات الحذف (خارج الجدول لتجنب التداخل) --}}
    @foreach ($exams as $exam)
        <div class="modal fade" id="deleteModal{{ $exam->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                    <div class="modal-body p-5 text-center">
                        <div class="text-danger mb-4"><i class="fas fa-exclamation-circle fa-4x"></i></div>
                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                        <p class="text-muted">هل أنت متأكد من حذف إختبار <strong>({{ $exam->subject->name }})</strong>؟</p>
                        <div class="d-flex justify-content-center mt-4" style="gap: 10px;">
                            <button type="button" class="btn btn-light px-4 rounded-pill" data-dismiss="modal">إلغاء</button>
                            <form action="{{ route('exams.destroy', $exam->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger px-4 rounded-pill">تأكيد الحذف</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection