@extends('layout-cms.main-layout')
@section('title', 'صفحة تسجيل الحضور والغياب')

@section('style')
    <style>
        /* تمييز ألوان الحالات */
        .status-select[data-status="present"] {
            border-left: 5px solid #28a745;
        }

        .status-select[data-status="absent"] {
            border-left: 5px solid #dc3545;
        }

        .status-select[data-status="excused"] {
            border-left: 5px solid #ffc107;
        }

        .status-select[data-status="late"] {
            border-left: 5px solid #17a2b8;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: 0.3s;
        }

        .search-box {
            border-radius: 20px;
            padding-left: 15px;
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">تسجيل حضور الطلاب ليوم {{ date('Y-m-d') }}</h5>
        </div>

        <div class="card-body">
            <x-grade-level-error-component />
            <x-grade-level-success-component />

            <!-- أدوات التحكم: التاريخ والبحث -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold">تاريخ اليوم</label>
                    <input type="date" name="attendance_date" form="attendanceForm"
                        value="{{ old('attendance_date', date('Y-m-d')) }}" class="form-control shadow-sm">
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-bold">بحث عن طالب</label>
                    <input type="text" id="studentSearch" class="form-control search-box shadow-sm"
                        placeholder="ابحث باسم الطالب هنا...">
                </div>
            </div>

            <form action="{{ route('attendance.store') }}" method="POST" id="attendanceForm">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="attendanceTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50px">#</th>
                                <th>اسم الطالب رباعي</th>
                                <th width="250px">حالة الحضور</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr class="student-row">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="student-name fw-bold">{{ $student->full_name }}</td>
                                    <td>
                                        <input type="hidden" name="attendances[{{ $index }}][student_id]"
                                            value="{{ $student->id }}">

                                        {{-- استخدام دالة old لاسترجاع القيمة المختارة سابقاً --}}
                                        <select name="attendances[{{ $index }}][status]">
                                            <option value="present">حاضر ✅</option>
                                            <option value="absent">غائب ❌</option>
                                            <option value="excused">غائب بعذر ⚠️</option>
                                            <option value="late">متأخر ⏰</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5 shadow">حفظ </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // محرك البحث (Vanilla JS)
        document.getElementById('studentSearch').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.student-row');

            rows.forEach(row => {
                let name = row.querySelector('.student-name').textContent.toLowerCase();
                row.style.display = name.includes(filter) ? "" : "none";
            });
        });

        // تحديث ألوان الحالة عند تحميل الصفحة (في حال وجود قيم old)
        document.querySelectorAll('.status-select').forEach(select => {
            select.setAttribute('data-status', select.value);
        });
    </script>
@endsection
