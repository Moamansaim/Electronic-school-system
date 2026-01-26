@extends('layout-cms.main-layout')
@section('title', 'قائمة المواد الدراسية')

@section('content')
    <div class="container-fluid p-4">
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
                    <table class="table table-hover align-middle mb-0">
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
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('subjects.edit', $subject->id) }}"
                                                class="btn btn-sm btn-info mr-2 d-flex align-items-center" title="تعديل">
                                             <i class="fas fa-pen mr-1"></i>
                                                <span>تعديل</span>
                                            </a>

                                            <button class="btn btn-sm btn-danger mr-2 d-flex align-items-center" data-toggle="modal"
                                                data-target="#deleteModal{{ $subject->id }}" title="حذف">
                                                  <i class="fas fa-trash-alt mr-1"></i>
                                                <span>حذف</span>
                                            </button>
                                        </div>

                                        <div class="modal fade" id="deleteModal{{ $subject->id }}" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                                    <div class="modal-body p-5 text-center">
                                                        <div class="text-danger mb-4">
                                                            <i class="fas fa-exclamation-circle fa-4x"></i>
                                                        </div>
                                                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                                                        <p class="text-muted">هل أنت متأكد من حذف
                                                            <strong>({{ $subject->name }})</strong>؟<br>هذا الإجراء لا يمكن
                                                            التراجع عنه.</p>
                                                        <div class="d-flex justify-content-center mt-4">
                                                            <button type="button"
                                                                class="btn btn-light px-4 mr-2 rounded-pill"
                                                                data-dismiss="modal">إلغاء</button>
                                                            <form action="{{ route('subjects.destroy', $subject->id) }}"
                                                                method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger px-4 rounded-pill">تأكيد
                                                                    الحذف</button>
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

    <style>
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
    </style>
@endsection
