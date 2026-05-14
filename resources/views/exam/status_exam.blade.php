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
                                    $maxMark = $attempt->exam->total_marks ?? 100;
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
                                                class="font-weight-bold text-dark student-name">{{ $student->full_name }}</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. نظام البحث (Vanilla JS)
            const searchInput = document.getElementById('studentSearch');
            searchInput.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('.student-row');

                rows.forEach(row => {
                    const name = row.querySelector('.student-name').textContent.toLowerCase();
                    row.style.display = name.includes(filter) ? "" : "none";
                });
            });

            // 2. تحديث الدرجة (Vanilla JS Fetch API)
            const scoreInputs = document.querySelectorAll('.score-input');
            scoreInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const attemptId = this.dataset.attemptId;
                    const maxMark = parseFloat(this.dataset.max);
                    const newScore = parseFloat(this.value);
                    const errorMsg = this.nextElementSibling;

                    // التحقق من النطاق
                    if (newScore > maxMark || newScore < 0) {
                        this.classList.add('is-invalid-mark');
                        errorMsg.classList.remove('d-none');
                        errorMsg.textContent = 'الحد الأقصى: ' + maxMark;
                        return;
                    } else {
                        this.classList.remove('is-invalid-mark');
                        errorMsg.classList.add('d-none');
                    }

                    this.style.opacity = '0.5';

                    // إرسال الطلب عبر Fetch API
                    fetch("{{ route('exam.update-score') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                attempt_id: attemptId,
                                score: newScore
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.style.opacity = '1';
                            if (data.success) {
                                this.classList.add('is-updated');
                                setTimeout(() => {
                                    this.classList.remove('is-updated');
                                }, 2000);
                            } else {
                                alert(data.message || 'فشل التحديث');
                            }
                        })
                        .catch(error => {
                            this.style.opacity = '1';
                            console.error('Error:', error);
                            alert('حدث خطأ أثناء الاتصال بالخادم');
                        });
                });
            });
        });
    </script>
@endsection
