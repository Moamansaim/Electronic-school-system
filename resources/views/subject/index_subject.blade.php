@extends('layout-cms.main-layout')
@section('title', 'قائمة المواد الدراسية')

@section('content')
    <div class="container-fluid ">
        <x-grade-level-success-component />

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-book text-primary mr-2"></i> المواد الدراسية
                        </h4>
                    </div>
                    <div class="col-md-8 text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <form action="{{ route('subjects.index') }}" method="GET" class="mr-3">
                                <div class="input-group border rounded-pill px-2 py-1 bg-light">
                                    <input type="text" name="search" class="form-control bg-transparent border-0"
                                        placeholder="ابحث عن مادة أو مرحلة..." value="{{ request('search') }}"
                                        style="width: 200px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-link text-muted" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <a href="{{ route('subjects.create') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
                                <i class="fas fa-plus mr-1"></i> إضافة مادة دراسية جديدة
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table  table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 text-center text-muted py-3" style="width: 50px;">#</th>
                                <th class="border-0 text-muted py-3">المادة الدراسية </th>
                                <th class="border-0 text-muted py-3">المرحلة الدراسية</th>
                                <th class="border-0 text-muted py-3">تاريخ الإضافة</th>
                                <th class="border-0 text-center text-muted py-3">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects as $subject)
                                <tr>
                                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-soft-primary rounded p-2 mr-3"
                                                style="background-color: #e7f1ff; color: #007bff;">
                                                <i class="fas fa-book ml-1"></i>
                                            </div>
                                            <span class="font-weight-bold text-dark">{{ $subject->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-light border px-3 py-2 text-primary">
                                            {{ $subject->gradeLevel->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="far fa-clock mr-1"></i> {{ $subject->created_at }}
                                        </small>
                                    </td>
                                    <td class="text-center" style="overflow: visible;">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-pill shadow-sm px-3" type="button"
                                                id="dropdownMenuButton{{ $subject->id }}" data-toggle="dropdown"
                                                data-boundary="window" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v text-muted"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right border-0 shadow-lg mt-2"
                                                aria-labelledby="dropdownMenuButton{{ $subject->id }}"
                                                style="border-radius: 12px; min-width: 180px; z-index: 1050; text-align: right;">

                                                <h6 class="dropdown-header text-xs text-uppercase text-muted font-weight-bold">
                                                    خيارات المادة
                                                </h6>

                                                <a class="dropdown-item" href="{{ route('subjects.show', $subject->id) }}">
                                                    <i class="fas fa-cloud-upload-alt text-dark mr-2"></i> رفع ملخصات
                                                </a>

                                                <a class="dropdown-item" href="{{ route('files.view', $subject->id) }}">
                                                    <i class="fas fa-folder-open mr-2" style="color: #6f42c1;"></i> عرض المرفقات
                                                </a>

                                                <a class="dropdown-item" href="{{ route('subjects.edit', $subject->id) }}">
                                                    <i class="fas fa-pen text-info mr-2"></i> تعديل المادة
                                                </a>

                                                <div class="dropdown-divider"></div>

                                                <button type="button" class="dropdown-item text-danger" data-toggle="modal"
                                                    data-target="#deleteModal{{ $subject->id }}">
                                                    <i class="fas fa-trash-alt mr-2"></i> حذف المادة
                                                </button>
                                            </div>
                                        </div>
                                    </td>


                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50">
                                        <p class="text-muted">لم يتم العثور على أي مواد دراسية حالياً.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($subjects->hasPages())
                <div class="card-footer bg-white border-top-0 py-4 d-flex justify-content-center">
                    {{ $subjects->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    @foreach ($subjects as $subject)
        <div class="modal fade" id="deleteModal{{ $subject->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                    <div class="modal-body p-5 text-center">
                        <div class="text-danger mb-4">
                            <i class="fas fa-exclamation-circle fa-4x"></i>
                        </div>
                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                        <p class="text-muted">هل أنت متأكد من حذف
                            <strong>({{ $subject->name }})</strong>؟<br>هذا الإجراء لا يمكن
                            التراجع عنه.
                        </p>
                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn-light px-4 mr-2 rounded-pill"
                                data-dismiss="modal">إلغاء</button>
                            <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger px-4 rounded-pill">تأكيد
                                    الحذف</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <style>
        /* محاذاة عامة للجدول */
        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        /* استثناء عمود اسم المرحلة (يحتوي أيقونة + نص) */
        .table tbody td:nth-child(2) {
            text-align: right;
        }

        /* تحسين مظهر الأزرار */
        .btn-outline-info:hover,
        .btn-outline-secondary:hover,
        .btn-outline-success:hover {
            color: white !important;
        }

        /* توحيد الأيقونات الدائرية */
        .rounded-circle {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        /* توحيد المسافات بين الأزرار */
        .gap-2 {
            gap: 0.5rem;
        }
    </style>

@endsection