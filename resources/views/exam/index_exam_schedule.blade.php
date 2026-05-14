@extends('layout-cms.main-layout')
@section('content')
    <div class="container mt-5">
          <x-grade-level-success-component />
        <x-grade-level-error-component />
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">{{ $schedules->schedule_title }}</h2>
            <a href="{{ route('exam-schedules.create') }}"
                class="btn btn-primary shadow-sm px-4 rounded-pill font-weight-bold">
                <i class="fas fa-plus ml-1"></i> إضافة موعد جديد
            </a>
        </div>

        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-hover align-middle bg-white">
                <thead class="table-light">
                    <tr class="text-center border-bottom-2">
                        <th scope="col" style="width: 50px;" class="bg-light">#</th>
                        <th scope="col">اسم المادة</th>
                        <th scope="col">اليوم</th>
                        <th scope="col">التاريخ</th>
                        <th scope="col">وقت البدء</th>
                        <th scope="col">وقت الانتهاء</th>
                        <th scope="col">العمليات</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($schedules->dataExamSchedules as $data)
                        <tr class="text-center">
                            <td class="fw-bold bg-light text-secondary">{{ $loop->iteration }}</td>
                            <td class="text-start ps-4 fw-medium">{{ $data->subject->name }}</td> {{-- يفضل ربطها بعلاقة لجلب
                            اسم المادة --}}
                            <td><span class="badge bg-info text-dark">{{ $data->exam_day }}</span></td>
                            <td>{{ $data->exam_date }}</td>
                            <td class="text-success fw-bold">{{ $data->start_time }}</td>
                            <td class="text-danger fw-bold">{{ $data->end_time }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('data_exam_schedules.edit', $data->id) }}"
                                        class="btn btn-sm btn-info mr-2 d-flex align-items-center" title="تعديل">
                                        <i class="fas fa-pen mr-1"></i>
                                        <span>تعديل</span>
                                    </a>

                                    <button class="btn btn-sm btn-danger d-flex align-items-center" data-toggle="modal"
                                        data-target="#deleteModal{{ $data->id }}" title="حذف">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        <span>حذف</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($schedules->dataExamSchedules as $data)
        <div class="modal fade" id="deleteModal{{ $data->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                    <div class="modal-body p-5 text-center">
                        <div class="text-danger mb-4">
                            <i class="fas fa-exclamation-circle fa-4x"></i>
                        </div>
                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                        <p class="text-muted">هل أنت متأكد من حذف موعد اختبار
                            <strong>({{ $data->subject->name }})</strong>؟<br>هذا الإجراء لا
                            يمكن التراجع عنه.
                        </p>
                        <div class="d-flex justify-content-center mt-4">
                            <button type="button" class="btn btn-light px-4 mr-2 rounded-pill"
                                data-dismiss="modal">إلغاء</button>
                            <form action="{{ route('data_exam_schedules.destroy', $data->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
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
        /* لمسات لجعل الجدول يشبه الإكسل */
        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6 !important;
            padding: 12px;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f1faff;
            /* لون خفيف عند المرور بالفأرة */
        }
    </style>


@endsection