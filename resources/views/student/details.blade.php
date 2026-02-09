
@extends('layout-cms.main-layout')
@section('title', 'الملف الأكاديمي | ' . $student->full_name)

@section('content')
    <div class="container-fluid p-4" dir="rtl">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap no-print">
            <div>
                <h4 class="font-weight-bold text-secondary mb-0">بطاقة الطالب الأكاديمية</h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('students.index') }}" class="btn btn-secondary shadow-sm px-4 rounded-pill mr-2">
                    <I class="fas fa-chevron-right ml-1"></i> العودة للقائمة
                </a>
                <button onclick="window.print();" class="btn btn-primary shadow-sm px-4 rounded-pill">
                    <I class="fas fa-print ml-1"></i> طباعة الملف
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header border-0 pb-0 pt-4 bg-white text-center">
                        <div class="avatar-container position-relative">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                                Style="background: linear-gradient(135deg, #4e73df, #224abe); color: #fff; width: 100px; height: 100px; font-size: 2.5rem;">
                                {{ mb_substr($student->first_name, 0, 1) }}
                            </div>
                        </div>
                        <h5 class="font-weight-bold mt-3 mb-1 text-dark">{{ $student->full_name }}</h5>
                        <h6 class="text-muted">رقم الطالب: {{ $student->user->school_id ?? '---' }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-list">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box-sm bg-soft-info ml-3"><I class="fas fa-fingerprint text-info"></i></div>
                                <div>
                                    <small class="text-muted d-block">الهوية الوطنية</small>
                                    <span class="font-weight-bold small">{{ $student->national_id }}</span>
                                </div>
                            </div>
                            <div class="mt-4 p-3 rounded bg-light">
                                <h6 class="small font-weight-bold text-primary mb-2"><I class="fas fa-shield-alt ml-1"></i> الحالة الأكاديمية</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">المرحلة:</span>
                                    <span class="small font-weight-bold">{{ $student->gradeLevel->name ?? 'غير محدد' }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted">الصف:</span>
                                    <span class="small font-weight-bold text-success">{{ $student->classroom->name ?? 'غير محدد' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <ul class="nav nav-pills mb-4 bg-light p-1 rounded-pill no-print" id="studentTab" role="tablist" style="width: fit-content;">
                            <li class="nav-item">
                                <a class="nav-link active rounded-pill px-4" id="profile-tab" data-toggle="tab" href="#profile">البيانات الشخصية</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-4" id="schedule-tab" data-toggle="tab" href="#schedule">الجدول الدراسي</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="studentTabContent">
                            <div class="tab-pane fade show active" id="profile">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h6 class="section-title">الاسم الكامل</h6>
                                        <p class="h6 bg-light p-3 rounded border-right-bold">{{ $student->full_name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <h6 class="section-title">العنوان</h6>
                                        <p class="h6 bg-light p-3 rounded border-right-bold">{{ $student->city }} – {{ $student->district }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="schedule">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="section-title mb-0"><I class="fas fa-calendar-alt ml-2"></i> جدول الحصص الأسبوعي</h6>
                                    <span class="badge badge-soft-primary px-3 py-2 rounded-pill small">الفصل الدراسي الحالي</span>
                                </div>

                                <div class="table-responsive shadow-sm rounded border">
                                    <table class="table table-bordered mb-0 text-center schedule-table">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th class="align-middle" style="width: 120px;">اليوم / الحصة</th>
                                                @foreach($periods as $period)
                                                    <th class="py-3 small font-weight-bold">{{ $period }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($days as $day)
                                                <tr>
                                                    <td class="bg-light align-middle font-weight-bold text-dark small">{{ $day }}</td>
                                                    @foreach($periods as $period)
                                                        <td class="p-2 align-middle" style="min-width: 130px; height: 100px;">
                                                            @if(isset($schedules[$day][$period]))
                                                                <div class="schedule-entry shadow-sm">
                                                                    <div class="subj-name">{{ $schedules[$day][$period]->subject_name }}</div>
                                                                    <div class="teach-name text-muted mt-1">
                                                                        <I class="fas fa-user-tie ml-1 small"></i>{{ $schedules[$day][$period]->teacher_full_name }}
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="text-light">-</div>
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

    <style>
        /* التنسيق العام */
        :root { --primary-color: #4e73df; --soft-blue: #eef2ff; }
        .bg-soft-info { background: #e3f2fd; }
        .icon-box-sm { width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .section-title { font-size: 0.8rem; font-weight: 700; color: #858796; text-transform: uppercase; display: block; }
        .border-right-bold { border-right: 4px solid var(--primary-color); }
        
        /* تنسيق جدول الحصص */
        .schedule-table thead th { border: none; font-size: 0.85rem; }
        .schedule-entry {
            Background: #fff;
            Border-right: 4px solid var(--primary-color);
            Border-radius: 8px;
            Padding: 12px 5px;
            Height: 100%;
            Display: flex;
            Flex-direction: column;
            Justify-content: center;
            Border-top: 1px solid #f0f0f0;
            Border-bottom: 1px solid #f0f0f0;
            Border-left: 1px solid #f0f0f0;
        }
        .subj-name { font-weight: 800; color: var(--primary-color); font-size: 0.85rem; }
        .teach-name { font-size: 0.7rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        
        /* الطباعة */
        @media print {
            .no-print { display: none !important; }
            .card { border: 1px solid #ddd !important; box-shadow: none !important; }
            .tab-content > .tab-pane { display: block !important; opacity: 1 !important; visibility: visible !important; }
            .schedule-entry { border: 1px solid #ddd !important; border-right: 4px solid var(--primary-color) !important; }
            Body { background: white !important; }
        }
    </style>
@endsection

