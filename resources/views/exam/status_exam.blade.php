@extends('layout-cms.main-layout')
@section('title', 'صفحة متابعة الاختبارات')

@section('content')
    <div class="container-fluid p-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-user-graduate text-primary mr-2"></i> متابعة تقدم الطلاب في الاختبارات
                        </h4>
                    </div>
                    <!-- مربع البحث -->
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="input-group" dir="rtl">
                            <input type="text" id="studentSearch" class="form-control border-primary"
                                placeholder="ابحث عن اسم الطالب..." style="border-radius: 0 10px 10px 0;">
                            <div class="input-group-append">
                                <span class="input-group-text bg-primary text-white border-primary"
                                    style="border-radius: 10px 0 0 10px;">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" dir="rtl">
                    <table class="table text-center table-hover align-middle mb-0" id="studentsTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 text-muted py-3 px-4">#</th>
                                <th class="border-0 text-muted py-3 px-4">اسم الطالب</th>
                                <th class="border-0 text-muted py-3">الحالة</th>
                                <th class="border-0 text-muted py-3">وقت البدء</th>
                                <th class="border-0 text-muted py-3">الدرجة الكلية</th>
                                <th class="border-0 text-muted py-3">درجة الطالب</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                @php
                                    $attempt = $student->examAttempt->first();
                                    $maxMark = $attempt->exam->total_marks ?? 100; // القيمة الافتراضية إذا لم توجد
                                @endphp
                                <tr class="student-row">
                                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-soft-primary rounded p-2 mr-3"
                                                style="background-color: #e7f1ff; color: #007bff;">
                                                <i class="fas fa-user "></i>
                                            </div>
                                            <span
                                                class="font-weight-bold text-dark  student-name">{{ $student->full_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($attempt && $attempt->status == 'submitted')
                                            <span class="badge badge-pill badge-success px-3 py-2">
                                                <i class="fas fa-check-circle ml-1"></i> تم التسليم
                                            </span>
                                        @elseif($attempt && $attempt->status == 'in progress')
                                            <span class="badge badge-pill badge-warning px-3 py-2 animate-pulse">
                                                <i class="fas fa-spinner fa-spin ml-1"></i> داخل الاختبار
                                            </span>
                                        @else
                                            <span
                                                class="badge badge-pill text-white badge-danger border px-3 py-2 text-muted">
                                                <i class="fas fa-clock ml-1"></i> لم يبدأ
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $attempt ? $attempt->start_at : '-' }}</td>
                                    <td><span class="max-mark-display">{{ $maxMark }}</span></td>
                                    <td style="width: 150px;">
                                        @if ($attempt)
                                            <div class="position-relative">
                                                <input type="number" class="form-control text-center score-input mx-auto"
                                                    value="{{ $attempt->final_score }}"
                                                    data-attempt-id="{{ $attempt->id }}" data-max="{{ $maxMark }}"
                                                    max="{{ $maxMark }}" min="0"
                                                    style="width: 90px; border-radius: 8px;"
                                                    title="الدرجة العظمى هي {{ $maxMark }}">
                                                <small class="text-danger error-msg d-none"
                                                    style="position: absolute; width: 100%; right: 0; bottom: -20px; font-size: 10px;">تجاوزت
                                                    الحد!</small>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table th {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle !important;
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .7;
            }
        }

        .bg-soft-primary {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .score-input {
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }

        .score-input.is-updated {
            border-color: #28a745 !important;
            background-color: #d4edda !important;
            color: #155724 !important;
        }

        .score-input.is-invalid-mark {
            border-color: #dc3545 !important;
            background-color: #f8d7da !important;
            color: #721c24 !important;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // 1. نظام البحث (Client-side)
            $("#studentSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".student-row").filter(function() {
                    $(this).toggle($(this).find('.student-name').text().toLowerCase().indexOf(
                        value) > -1)
                });
            });

            // 2. تحديث الدرجة مع التحقق من النطاق
            $('.score-input').on('change', function() {
                var input = $(this);
                var attemptId = input.data('attempt-id');
                var maxMark = parseFloat(input.data('max'));
                var newScore = parseFloat(input.val());
                var errorMsg = input.siblings('.error-msg');

                // التحقق من النطاق قبل الإرسال
                if (newScore > maxMark || newScore < 0) {
                    input.addClass('is-invalid-mark');
                    errorMsg.removeClass('d-none').text('الحد الأقصى: ' + maxMark);
                    return; // إيقاف العملية
                } else {
                    input.removeClass('is-invalid-mark');
                    errorMsg.addClass('d-none');
                }

                input.css('opacity', '0.5');

                $.ajax({
                    url: "{{ route('exam.update-score') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        attempt_id: attemptId,
                        score: newScore
                    },
                    success: function(response) {
                        input.css('opacity', '1');
                        if (response.success) {
                            input.addClass('is-updated');
                            setTimeout(function() {
                                input.removeClass('is-updated');
                            }, 2000);
                        } else {
                            alert(response.message || 'فشل التحديث');
                        }
                    },
                    error: function(xhr) {
                        input.css('opacity', '1');
                        // التعامل مع أخطاء التحقق القادمة من السيرفر (422)
                        if (xhr.status === 422) {
                            alert('الدرجة المدخلة غير صالحة أو تتجاوز الدرجة الكلية.');
                        } else {
                            alert('حدث خطأ فني، حاول مرة أخرى.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
