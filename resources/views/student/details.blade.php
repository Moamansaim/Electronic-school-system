@extends('layout-cms.main-layout')
@section('title', 'الملف الأكاديمي | ' . $student->full_name)

@section('content')
    <div class="container-fluid p-4" dir="rtl">
        {{-- Header Section --}}
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
            {{-- Sidebar: Student Info --}}
            <div class="col-xl-3 col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-header border-0 pb-0 pt-4 bg-white text-center">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                            style="background: linear-gradient(135deg, #4e73df, #224abe); color: #fff; width: 100px; height: 100px; font-size: 2.5rem;">
                            {{ mb_substr($student->first_name, 0, 1) }}
                        </div>
                        <h5 class="font-weight-bold mt-3 mb-1 text-dark">{{ $student->full_name }}</h5>
                        <h6 class="text-muted small">رقم الطالب: {{ $student->user->school_id ?? '---' }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="info-list">
                            <div class="d-flex align-items-center mb-3 text-right">
                                <div class="icon-box-sm bg-soft-info ml-3">
                                    <i class="fas fa-id-card text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">الهوية الوطنية</small>
                                    <span class="font-weight-bold small">{{ $student->national_id }}</span>
                                </div>
                            </div>
                            <div class="mt-4 p-3 rounded bg-light">
                                <h6 class="small font-weight-bold text-primary mb-2 text-right">
                                    <i class="fas fa-shield-alt ml-1"></i> الحالة الأكاديمية
                                </h6>
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
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content: Tabs --}}
            <div class="col-xl-9 col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-body p-4 text-right">
                        <ul class="nav nav-pills mb-4 bg-light p-1 rounded-pill no-print" id="studentTab" role="tablist"
                            style="width: fit-content;">
                            <li class="nav-item">
                                <a class="nav-link active rounded-pill px-4" id="grades-tab" data-toggle="tab"
                                    href="#grades">سجل الدرجات</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-4" id="attendance-tab" data-toggle="tab"
                                    href="#attendance">سجل الحضور</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-4" id="schedule-tab" data-toggle="tab"
                                    href="#schedule">الجدول الدراسي</a>
                            </li>
                        </ul>

                        <div class="tab-content text-right" id="studentTabContent">
                            {{-- Tab 1: Grades --}}
                            <div class="tab-pane fade show active" id="grades">
                                <div class="row">
                                    @forelse($student->examAttempt as $attempt)
                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 border-0 shadow-sm grade-card"
                                                style="border-right: 5px solid #4e73df;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div class="badge-marks p-2 rounded text-center"
                                                            style="background: #f8f9fc; min-width: 80px;">
                                                            <div class="font-weight-bold text-primary">
                                                                {{ $attempt->final_score }}</div>
                                                            <small class="text-muted">من
                                                                {{ $attempt->exam->total_marks }}</small>
                                                        </div>
                                                        <span
                                                            class="badge badge-pill badge-primary px-3">{{ $attempt->exam->exam_type }}</span>
                                                    </div>
                                                    <h6 class="font-weight-bold text-dark mb-1">
                                                        {{ $attempt->exam->subject->name }}</h6>
                                                    <small class="text-muted">التاريخ:
                                                        {{ $attempt->created_at->format('Y-m-d') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5">
                                            <p class="text-muted">لا توجد درجات مسجلة.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Tab 2: Attendance with JS Pagination --}}
                            <div class="tab-pane fade" id="attendance">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="section-title mb-0"><i class="fas fa-calendar-check ml-2"></i> سجل الحضور
                                        والغياب</h6>
                                </div>
                                <div class="table-responsive shadow-sm rounded border">
                                    <table class="table table-hover mb-0 text-center" id="attendanceTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="py-3">التاريخ</th>
                                                <th class="py-3">اليوم</th>
                                                <th class="py-3">الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody id="attendanceBody">
                                            @forelse($student->attendance as $record)
                                                <tr class="attendance-row">
                                                    <td class="align-middle font-weight-bold">
                                                        {{ $record->attendance_date->format('Y-m-d') }}</td>
                                                    <td class="align-middle text-muted">
                                                        {{ $record->attendance_date->translatedFormat('l') }}</td>
                                                    <td class="align-middle">
                                                        <span
                                                            class="badge px-3 py-2 rounded-pill font-weight-bold status-{{ $record->status }}">
                                                            {{ $record->status_lable }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="py-5 text-muted">لا يوجد سجلات حضور.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- JS Pagination Controls --}}
                                <nav class="mt-4 no-print">
                                    <ul class="pagination justify-content-center" id="paginationControls"></ul>
                                </nav>
                            </div>

                            {{-- Tab 3: Schedule --}}
                            <div class="tab-pane fade" id="schedule">
                                <div class="table-responsive shadow-sm rounded border text-center">
                                    <table class="table table-bordered mb-0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>اليوم / الحصة</th>
                                                @foreach ($periods as $period)
                                                    <th>{{ $period }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($days as $day)
                                                <tr>
                                                    <td class="bg-light font-weight-bold">{{ $day }}</td>
                                                    @foreach ($periods as $period)
                                                        <td class="p-2" style="min-width: 130px; height: 100px;">
                                                            @if (isset($schedules[$day][$period]))
                                                                <div class="schedule-entry shadow-sm">
                                                                    <div class="subj-name">
                                                                        {{ $schedules[$day][$period]->subject_name }}</div>
                                                                    <div class="teach-name text-muted mt-1 small">
                                                                        {{ $schedules[$day][$period]->teacher_full_name }}
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
        }

        /* Status Badges */
        .status-present {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .status-absent {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .status-late {
            background-color: #fff3e0;
            color: #ef6c00;
            border: 1px solid #ffe0b2;
        }

        .status-excused {
            background-color: #e1f5fe;
            color: #0277bd;
            border: 1px solid #b3e5fc;
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rowsPerPage = 10; // عدد السجلات في كل صفحة
            const tableBody = document.getElementById('attendanceBody');
            const rows = Array.from(tableBody.getElementsByClassName('attendance-row'));
            const paginationContainer = document.getElementById('paginationControls');
            let currentPage = 1;

            function displayPage(page) {
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                rows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? '' : 'none';
                });

                updatePaginationButtons(page);
            }

            function updatePaginationButtons(activePage) {
                const pageCount = Math.ceil(rows.length / rowsPerPage);
                paginationContainer.innerHTML = '';

                if (pageCount <= 1) return; // لا حاجة للأزرار إذا كانت صفحة واحدة

                for (let i = 1; i <= pageCount; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === activePage ? 'active' : ''}`;

                    const a = document.createElement('a');
                    a.className = 'page-link rounded-circle mx-1 shadow-sm';
                    a.href = '#';
                    a.innerText = i;

                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage = i;
                        displayPage(i);
                    });

                    li.appendChild(a);
                    paginationContainer.appendChild(li);
                }
            }

            if (rows.length > 0) {
                displayPage(1);
            }
        });
    </script>
@endsection
