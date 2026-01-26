@extends('layout-cms.main-layout')
@section('title', 'قائمة المراحل الدراسية')

@section('content')
    <div class="container-fluid p-4" dir="rtl">
        <x-grade-level-success-component />

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col-xl-3 col-lg-4">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-graduation-cap text-primary ml-2"></i> المراحل الدراسية
                        </h4>
                    </div>
                    <div class="col-xl-9 col-lg-8 text-left">
                        <div class="d-flex flex-wrap justify-content-end align-items-center" style="gap: 10px;">

                            {{-- نموذج البحث --}}
                            <form action="{{ route('grade_levels.index') }}" method="GET" class="ml-2">
                                <div class="input-group border rounded-pill px-2 py-1 bg-light shadow-sm">
                                    <input type="text" name="search" class="form-control bg-transparent border-0"
                                        placeholder="ابحث عن مرحلة..." value="{{ request('search') }}"
                                        style="width: 180px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-link text-muted p-0 px-2" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <a href="{{ route('classrooms.index') }}"
                                class="btn btn-secondary shadow-sm px-3 rounded-pill">
                                <i class="fas fa-door-open ml-1"></i> عرض الصفوف
                            </a>

                            <a href="{{ route('grade_levels.create') }}"
                                class="btn btn-primary shadow-sm px-4 rounded-pill font-weight-bold">
                                <i class="fas fa-plus ml-1"></i> إضافة مرحلة
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
                                <th class="border-0  py-3" style="width: 60px;">#</th>
                                <th class="border-0 text-muted py-3 ">المرحلة الدراسية</th>
                                <th class="border-0 text-muted py-3 ">عدد الصفوف المسجلة</th>
                                <th class="border-0 text-muted py-3 ">تاريخ الإضافة</th>
                                <th class="border-0  text-muted py-3" style="width: 150px;">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($grade_levels as $grade_level)
                                <tr>
                                    <td class=" font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class="">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <div class="bg-soft-primary rounded p-2 mr-3"
                                                style="background-color: #e7f1ff; color: #007bff;">
                                                <i class="fas fa-graduation-cap text-primary "></i>
                                            </div>
                                            <span class="font-weight-bold text-dark">{{ $grade_level->name }}</span>
                                        </div>
                                    </td>
                                    <td class="">
                                        <span class="badge badge-pill badge-light border px-3 py-2 text-primary"
                                            style="font-size: 0.9rem;">
                                            {{ $grade_level->classrooms->count() }} صفوف
                                        </span>
                                    </td>
                                    <td class=" text-muted">
                                        <small><i class="far fa-calendar-alt ml-1"></i>
                                            {{ $grade_level->created_at }}</small>
                                    </td>
                                    <td class="">
                                        <div class="d-flex justify-content-center" style="gap: 8px;">
                                            <a href="{{ route('grade_levels.edit', $grade_level->id) }}"
                                                class="btn btn-sm btn-info mr-2 d-flex align-items-center" title="تعديل">
                                                <i class="fas fa-pen mr-1"></i>
                                                <span>تعديل</span>
                                            </a>

                                            <button class="btn btn-sm btn-danger d-flex align-items-center"
                                                data-toggle="modal" data-target="#deleteModal{{ $grade_level->id }}"
                                                title="حذف">
                                                <i class="fas fa-trash-alt mr-1"></i>
                                                <span>حذف</span>
                                            </button>
                                        </div>

                                        {{-- مودل الحذف --}}
                                        <div class="modal fade" id="deleteModal{{ $grade_level->id }}" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                                    <div class="modal-body p-5 ">
                                                        <div class="text-danger mb-4">
                                                            <i class="fas fa-exclamation-circle fa-4x"></i>
                                                        </div>
                                                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                                                        <p class="text-muted">هل أنت متأكد من حذف مرحلة
                                                            <strong>({{ $grade_level->name }})</strong>؟<br>سيؤدي هذا لحذف
                                                            كافة البيانات المرتبطة بها.
                                                        </p>
                                                        <div class="d-flex justify-content-center mt-4" style="gap: 10px;">
                                                            <button type="button"
                                                                class="btn btn-light px-4 rounded-pill shadow-sm font-weight-bold"
                                                                data-dismiss="modal">إلغاء</button>
                                                            <form
                                                                action="{{ route('grade_levels.destroy', $grade_level->id) }}"
                                                                method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger px-4 rounded-pill shadow-sm font-weight-bold">تأكيد
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
                                    <td colspan="5" class="py-5 ">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50">
                                        <p class="text-muted font-weight-bold">لم يتم العثور على أي مراحل دراسية حالياً.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($grade_levels->hasPages())
                <div class="card-footer bg-white border-top-0 py-4 d-flex justify-content-center">
                    {{ $grade_levels->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

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
