@extends('layout-cms.main-layout')
@section('title', 'تعيينات المعلم الدراسية')

@section('content')
    <div class="container-fluid p-4">
        <x-grade-level-success-component />

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-chalkboard-teacher text-primary mr-2"></i>
                            تعيينات المعلم: <span class="text-primary">
                                <span class="text-primary font-weight-bold">
                                    ({{ $teacher->first_name }}
                                    {{ $teacher->father_name }}
                                    {{ $teacher->grandfather_name }}
                                    {{ $teacher->family_name }})
                                </span>
                        </h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary rounded-pill">
                            <i class="fas fa-arrow-right mr-1"></i> العودة لقائمة المعلمين
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 text-center text-muted py-3" style="width: 50px;">#</th>
                                <th class="border-0 text-muted py-3">المرحلة الدراسية</th>
                                <th class="border-0 text-muted py-3">الصف الدراسي</th>
                                <th class="border-0 text-muted py-3">المادة الدراسية</th>
                                <th class="border-0  text-muted py-3">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teacher->teacherAssignments as $assignment)
                                <tr>
                                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape bg-soft-info text-info mr-2">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <span class="font-weight-bold text-dark">
                                                {{ $assignment->gradeLevel->name ?? 'غير محدد' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape bg-soft-primary text-primary mr-2">
                                                <i class="fas fa-door-open"></i>
                                            </div>
                                            <span class="text-dark">
                                                {{ $assignment->classroom->name ?? 'غير محدد' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape bg-soft-success text-success mr-2">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <span class="badge badge-success-light p-2">
                                                {{ $assignment->subject->name ?? 'غير محدد' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="text-center">

                                        <button class="btn btn-sm btn-danger d-flex align-items-center " data-toggle="modal"
                                            data-target="#deleteModal{{ $assignment->id }}" title="حذف">
                                            <i class="fas fa-trash-alt mr-1"></i>
                                            <span>حذف التعيين</span>
                                        </button>
                                        <div class="modal fade" id="deleteModal{{ $assignment->id }}" tabindex="-1"
                                            role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                                    <div class="modal-body p-5 text-center">
                                                        <div class="text-danger mb-4">
                                                            <i class="fas fa-exclamation-circle fa-4x"></i>
                                                        </div>
                                                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                                                        <p class="text-muted">هل أنت متأكد من حذف
                                                            <strong>التعيين</strong>؟<br>
                                                            <strong>ملاحظة: سيتم الحذف بشكل نهائي</strong>
                                                        </p>
                                                        <div class="d-flex justify-content-center mt-4">
                                                            <button type="button"
                                                                class="btn btn-light px-4 mr-2 rounded-pill"
                                                                data-dismiss="modal">إلغاء</button>
                                                            <form
                                                                action="{{ route('teachers.destroyAssignment', $assignment->id) }}"
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
                                        <p class="text-muted">لا يوجد تعيينات دراسية مسجلة لهذا المعلم حالياً.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table td,
        .table th {
            vertical-align: middle;
        }

        /* تصميم الأيقونات الجانبية */
        .icon-shape {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 14px;
        }

        /* ألوان ناعمة للخلفيات */
        .bg-soft-info {
            background-color: #e0f7fa;
            color: #00bcd4;
        }



        .bg-soft-primary {
            background-color: #e7f1ff;
            color: #007bff;
        }

        .bg-soft-success {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .badge-success-light {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .mr-2 {
            margin-left: 0.5rem !important;
        }

        /* للتوافق مع RTL إذا لزم الأمر */
    </style>
@endsection
