@extends('layout-cms.main-layout')
@section('title', 'قائمة المعلمين')

@section('content')
    <div class="container-fluid p-4">
        <x-grade-level-success-component />


        @if (session('generated_school_id'))
            <div class="alert alert-success"
                style="background: #e6fffa; border: 1px solid #38b2ac; padding: 20px; border-radius: 8px;">
                <h4 style="color: #2c7a7b; font-weight: bold;">✅ تم إنشاء الحساب!</h4>
                <p style="color: #2c7a7b; font-weight: bold;">بيانات الدخول جاهزة للنسخ:</p>

                <div id="fullAccountInfo" class="p-3 bg-white border rounded mt-2">
                    <strong>الرقم المدرسي الخاص بالمعلم:</strong> {{ session('generated_school_id') }} <br>
                    <strong>كلمة المرور:</strong> {{ session('generated_password') }}
                </div>

                <button onclick="copyAllInfo()" class="btn btn-primary mt-3"
                    style="background: #3182ce; color: white; padding: 8px 15px; border-radius: 5px; border: none; cursor: pointer;">
                    نسخ البيانات كاملة
                </button>
            </div>

            <script>
                function copyAllInfo() {
                    // تنسيق النص المراد نسخه (الإيميل في سطر وكلمة المرور في سطر)
                    const school_id = "{{ session('generated_school_id') }}";
                    const pass = "{{ session('generated_password') }}";
                    const textToCopy = "SchoolID: " + school_id + "\nPassword: " + pass;

                    // استخدام Clipboard API للنسخ
                    navigator.clipboard.writeText(textToCopy).then(function () {
                        alert('تم نسخ الإيميل وكلمة المرور معاً بنجاح!');
                    }).catch(err => {
                        console.error('فشل النسخ: ', err);
                    });
                }
            </script>
        @endif



        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-chalkboard-teacher text-primary mr-2"></i> قائمة المعلمين
                        </h4>
                    </div>
                    <div class="col-md-8 text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <form action="{{ route('teachers.index') }}" method="GET" class="mr-3">
                                <div class="input-group border rounded-pill px-2 py-1 bg-light">
                                    <input type="text" name="search" class="form-control bg-transparent border-0"
                                        placeholder="ابحث بالاسم أو الهوية..." value="{{ request('search') }}"
                                        style="width: 250px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-link text-muted" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <a href="{{ route('teachers.create') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
                                <i class="fas fa-plus mr-1"></i> إضافة معلم جديد
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
                                <th class="border-0 text-muted py-3">المعلم</th>
                                <th class="border-0 text-muted py-3">رقم الهوية</th>
                                <th class="border-0 text-muted py-3">العنوان</th>
                                <th class="border-0 text-muted py-3">أرقام التواصل</th>
                                <th class="border-0 text-center text-muted py-3">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teachers as $teacher)
                                <tr>
                                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-soft-primary rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center"
                                                style="background-color: #e7f1ff; color: #007bff; width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <span class="font-weight-bold text-dark d-block">
                                                    {{ $teacher->full_name }}
                                                </span>
                                                <small class="text-muted">تاريخ الميلاد:
                                                    {{ $teacher->date_of_birth }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-secondary px-2 py-2 d-inline-flex align-items-center"
                                            style="font-size: 0.9rem; background-color: #f0f2f5; color: #495057; border: 1px solid #e1e4e8;">

                                            <i class="fas fa-id-card  text-primary" style="font-size: 1rem;"></i>

                                            <span class="font-weight-bold ml-2">{{ $teacher->national_id }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                            {{ $teacher->city }} - {{ $teacher->district }}
                                            <div class="text-muted">{{ $teacher->street }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($teacher->phoneNumbers && $teacher->phoneNumbers->count() > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($teacher->phoneNumbers as $phone)
                                                    <span class="badge badge-pill badge-light border text-dark mb-1">
                                                        <i class="fas fa-phone-alt fa-xs text-success mr-1"></i>
                                                        {{ $phone->phone_number }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted small">لا توجد أرقام</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="overflow: visible;">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-pill shadow-sm px-3" type="button"
                                                id="dropdownMenuButton{{ $teacher->id }}" data-toggle="dropdown"
                                                data-boundary="window" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v text-muted"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right border-0 shadow-lg mt-2"
                                                aria-labelledby="dropdownMenuButton{{ $teacher->id }}"
                                                style="border-radius: 12px; min-width: 180px; z-index: 1050; text-align: right;">

                                                <h6 class="dropdown-header text-xs text-uppercase text-muted font-weight-bold">
                                                    الخيارات</h6>

                                                <a class="dropdown-item py-2 d-flex align-items-center"
                                                    href="{{ route('teachers.assignments.data', $teacher->id) }}">
                                                    <i class="fas fa-eye text-dark ml-2"></i> <span>عرض التعيينات</span>
                                                </a>

                                                <a class="dropdown-item py-2 d-flex align-items-center"
                                                    href="{{ route('teachers.assignment', $teacher->id) }}">
                                                    <i class="fas fa-plus-circle text-primary ml-2"></i>
                                                    <span>إضافة تعيين</span>
                                                </a>

                                                <a class="dropdown-item py-2 d-flex align-items-center"
                                                    href="{{ route('class-schedules.create', $teacher->id) }}">
                                                    <i class="fas fa-plus-circle text-primary ml-2"></i>
                                                    <span>إضافة حصة دراسية</span>
                                                </a>

                                                <a class="dropdown-item py-2 d-flex align-items-center"
                                                    href="{{ route('class-schedules.show', $teacher->id) }}">
                                                    <i class="fas fa-eye text-dark ml-2"></i> <span>عرض جدول الحصص</span>
                                                </a>

                                                <a class="dropdown-item py-2 d-flex align-items-center"
                                                    href="{{ route('teachers.edit', $teacher->id) }}">
                                                    <i class="fas fa-pen text-info ml-2"></i>
                                                    <span>تعديل البيانات</span>
                                                </a>

                                                <div class="dropdown-divider"></div>

                                                <button type="button"
                                                    class="dropdown-item py-2 d-flex align-items-center text-danger"
                                                    data-toggle="modal" data-target="#deleteModal{{ $teacher->id }}">
                                                    <i class="fas fa-trash-alt ml-2"></i>
                                                    <span>حذف المعلم</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="deleteModal{{ $teacher->id }}" tabindex="-1" role="dialog"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                                    <div class="modal-body p-5 text-center">
                                                        <div class="text-danger mb-4">
                                                            <i class="fas fa-exclamation-circle fa-4x"></i>
                                                        </div>
                                                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                                                        <p class="text-muted">هل أنت متأكد من حذف المعلم <br>
                                                            <strong class="text-dark">({{ $teacher->first_name }}
                                                                {{ $teacher->family_name }})</strong>؟
                                                        </p>
                                                        <div class="d-flex justify-content-center mt-4">
                                                            <button type="button" class="btn btn-light px-4 ml-2 rounded-pill"
                                                                data-dismiss="modal">إلغاء</button>

                                                            <form action="{{ route('teachers.destroy', $teacher->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
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
                                    <td colspan="6" class="py-5 text-center">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50">
                                        <p class="text-muted">لم يتم العثور على أي معلمين حالياً.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($teachers->hasPages())
                <div class="card-footer bg-white border-top-0 py-4 d-flex justify-content-center">
                    {{ $teachers->withQueryString()->links() }}
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