@extends('layout-cms.main-layout')
@section('content')
    <div class="container mt-5">
        <x-grade-level-success-component />
        <x-grade-level-error-component />
        @php
            $mainSchedule = $examSchedule->first();
        @endphp
        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">
                {{ $mainSchedule->examSchedule->schedule_title }}
            </h2>
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
                    </tr>
                </thead>
                <tbody>

                    @forelse ($examSchedule as $data)
                        <tr class="text-center">
                            {{-- رقم السطر --}}
                            <td class="fw-bold bg-light text-secondary">{{ $loop->iteration }}</td>

                            {{-- اسم المادة (يتم جلبها عبر علاقة subject في موديل الجدول) --}}
                            <td class="text-start ps-4 fw-medium">
                                {{ $data->subject->name ?? 'مادة غير معروفة' }}
                            </td>

                            {{-- تفاصيل الموعد --}}
                            <td>
                                {{ $data->exam_day }}
                            </td>
                            <td>{{ $data->exam_date }}</td>
                            <td class="text-success fw-bold">{{ $data->start_time }}</td>
                            <td class="text-danger fw-bold">{{ $data->end_time }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-4 text-muted">
                                لا توجد اختبارات مجدولة للمواد المسجلة لك حالياً.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>



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