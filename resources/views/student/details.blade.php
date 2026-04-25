@extends('layout-cms.main-layout')
@section('title', 'الملف الأكاديمي | ' . $student->full_name)

@section('content')
    <div class="container-fluid p-4" dir="rtl">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap no-print">
            <div>
                <h4 class="font-weight-bold text-secondary mb-0">بطاقة الطالب الأكاديمية</h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('students.index') }}" class="btn btn-secondary shadow-sm px-4 rounded-pill ml-2">
                    <i class="fas fa-chevron-right ml-1"></i> العودة للقائمة
                </a>
                <button onclick="window.print();" class="btn btn-primary shadow-sm px-4 rounded-pill">
                    <i class="fas fa-print ml-1"></i> طباعة الملف
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header border-0 pb-0 pt-4 bg-white text-center">
                        <div class="avatar-container position-relative">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                                style="background: linear-gradient(135deg, #4e73df, #224abe); color: #fff; width: 100px; height: 100px; font-size: 2.5rem;">
                                {{ mb_substr($student->first_name, 0, 1) }}
                            </div>
                        </div>
                        <h5 class="font-weight-bold mt-3 mb-1 text-dark">{{ $student->full_name }}</h5>
                        <h6 class="text-muted">رقم الطالب: {{ $student->user->school_id ?? '---' }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-list">
                            <div class="d-flex align-items-center mb-3 ">
                                <div class="icon-box-sm bg-soft-info ml-3">
                                    <i class="fas fa-id-card text-info"></i>
                                </div>
                                <div class="ml-2">
                                    <small class="text-muted d-block">الهوية الوطنية</small>
                                    <span class="font-weight-bold small">{{ $student->national_id }}</span>
                                </div>
                            </div>
                            <div class="mt-4 p-3 rounded bg-light">
                                <h6 class="small font-weight-bold text-primary mb-2"><i class="fas fa-shield-alt ml-1"></i>
                                    الحالة الأكاديمية</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">المرحلة:</span>
                                    <span
                                        class="small font-weight-bold">{{ $student->gradeLevel->name ?? 'غير محدد' }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">الصف:</span>
                                    <span
                                        class="small font-weight-bold text-success">{{ $student->classroom->name ?? 'غير محدد' }}</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-warning btn-block mt-4 rounded-pill" data-toggle="modal"
                                data-target="#resetPasswordModal">
                                <i class="fas fa-key ml-1"></i> إعادة تعيين كلمة المرور
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <ul class="nav nav-pills mb-4 bg-light p-1 rounded-pill no-print" id="studentTab" role="tablist"
                            style="width: fit-content;">
                            <li class="nav-item">
                                <a class="nav-link active rounded-pill px-4" id="grades-tab" data-toggle="tab"
                                    href="#grades">سجل الدرجات</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-4" id="schedule-tab" data-toggle="tab"
                                    href="#schedule">الجدول الدراسي</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="studentTabContent">
                            <div class="tab-pane fade show active" id="grades">
                                <div class="row">
                                    @forelse($student->examAttempt as $attempt)
                                        @php
                                            $exam = $attempt->exam;
                                        @endphp
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm grade-card"
                                                style="border-right: 5px solid #4e73df;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div class="badge-marks p-2 rounded text-center"
                                                            style="background: #f8f9fc; min-width: 80px;">

                                                            <small class="text-muted">من {{ $exam->total_marks }}</small>
                                                        </div>
                                                        <div class="text-left text-md-right">

                                                            <div class="text-left text-md-right">
                                                                {{-- المقارنة مباشرة مع حالات الـ Enum --}}
                                                                
                                                                    @if ($exam->exam_type === \App\Enums\ExamType::Monthly->value)
                                                                        <span class="badge badge-pill badge-primary px-3">اختبار
                                                                            شهري</span>
                                                                    @elseif($exam->exam_type === \App\Enums\ExamType::Midterm->value)
                                                                        <span
                                                                            class="badge badge-pill badge-info px-3 text-white">اختبار
                                                                            نصفي</span>
                                                                    @elseif($exam->exam_type === \App\Enums\ExamType::Final->value)
                                                                        <span class="badge badge-pill badge-success px-3">اختبار
                                                                            نهائي</span>
                                                                    @endif
                                                                
                                                                {{-- إظهار الشهر فقط في حال كان الاختبار شهرياً --}}
                                                                @if ($exam->exam_type === \App\Enums\ExamType::Monthly->value && $exam->month)
                                                                    <div class="small text-muted mt-1">
                                                                        <i class="far fa-calendar-alt ml-1"></i> شهر:
                                                                        {{ $exam->month }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <h6 class="font-weight-bold text-dark mb-1">{{ $exam->subject->name }}
                                                    </h6>

                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted text-bold"> علامة الطالب
                                                            :{{ $attempt->final_score }}</span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5">
                                            <p class="text-muted">لا توجد درجات مسجلة لهذا الطالب.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="tab-pane fade" id="schedule">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="section-title mb-0"><i class="fas fa-calendar-alt ml-2"></i> جدول الحصص
                                        الأسبوعي</h6>
                                </div>
                                <div class="table-responsive shadow-sm rounded border">
                                    <table class="table table-bordered mb-0 text-center schedule-table">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th class="align-middle" style="width: 120px;">اليوم / الحصة</th>
                                                @foreach ($periods as $period)
                                                    <th class="py-3 small font-weight-bold">{{ $period }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($days as $day)
                                                <tr>
                                                    <td class="bg-light align-middle font-weight-bold text-dark small">
                                                        {{ $day }}
                                                    </td>
                                                    @foreach ($periods as $period)
                                                        <td class="p-2 align-middle" style="min-width: 130px; height: 100px;">
                                                            @if (isset($schedules[$day][$period]))
                                                                <div class="schedule-entry shadow-sm">
                                                                    <div class="subj-name">
                                                                        {{ $schedules[$day][$period]->subject_name }}
                                                                    </div>
                                                                    <div class="teach-name text-muted mt-1">
                                                                        <i
                                                                            class="fas fa-user-tie ml-1 small"></i>{{ $schedules[$day][$period]->teacher_full_name }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-hidden="true" dir="rtl">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bold text-danger">إعادة تعيين كلمة المرور</h5>
                    <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <p>هل أنت متأكد من رغبتك في إعادة تعيين كلمة مرور الطالب:</p>
                    <h5 class="text-primary font-weight-bold my-3">{{ $student->full_name }}</h5>
                    <p class="text-muted small">سيتم إعادة تعيينها إلى كلمة المرور الافتراضية للنظام.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">إلغاء</button>
                    <form action="" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger px-4">تأكيد التعيين</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color: #4e73df;
        }

        .bg-soft-info {
            background: #e3f2fd;
        }

        .icon-box-sm {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #858796;
            text-transform: uppercase;
            display: block;
        }

        .border-right-bold {
            border-right: 4px solid var(--primary-color);
        }

        .schedule-entry {
            background: #fff;
            border-right: 4px solid var(--primary-color);
            border-radius: 8px;
            padding: 10px;
            height: 100%;
            border: 1px solid #f0f0f0;
        }

        .subj-name {
            font-weight: 800;
            color: var(--primary-color);
            font-size: 0.85rem;
        }

        .grade-card {
            transition: all 0.3s ease;
        }

        .grade-card:hover {
            transform: translateY(-3px);
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection