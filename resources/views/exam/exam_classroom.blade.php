@extends('layout-cms.main-layout')

@section('content')
    <style>
        .classroom-card {
            border-radius: 20px;
            background: #fff;
            padding: 25px;
            transition: all 0.35s ease;
            border: 1px solid #f1f1f1;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .classroom-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: #0d6efd33;
        }

        .classroom-card .icon-box {
            width: 55px;
            height: 55px;
            border-radius: 15px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.4s;
        }

        .classroom-card:hover .icon-box {
            background: #0d6efd;
            color: #fff;
            transform: rotate(360deg);
        }

        .students-badge {
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #555;
            display: inline-flex;
            align-items: center;
        }

        .classroom-card:hover .students-badge {
            background: #e7f1ff;
            color: #0d6efd;
        }
    </style>

    <div class="container py-5" dir="rtl">
        {{-- العنوان --}}
        <div class="mb-5 border-end border-4 border-primary pe-3">
            <h2 class="fw-bold">متابعة الاختبارات</h2>
            <p class="text-muted mb-0">
                المادة: <span class="text-primary fw-bold">{{ $exam->subject->name }}</span>
            </p>
        </div>
        <div class="row g-4">
            @forelse($exam->classrooms as $classroom)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('exams.teacher.exams.monitor', [$exam->id, $classroom->id]) }}"
                        class="text-decoration-none">
                        <div class="classroom-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="text-primary small fw-bold">CLASSROOM</span>
                                    <h4 class="fw-bold mt-2 text-dark">
                                        {{ $classroom->name }}
                                    </h4>
                                    <div class="mt-3">
                                        <span class="students-badge">
                                            <i class="fas fa-users ms-2"></i>
                                            {{ $classroom->students_count ?? $classroom->students->count() }} طالب
                                        </span>
                                    </div>
                                </div>
                                <div class="icon-box">
                                    <i class="fas fa-arrow-left"></i>
                                </div>
                            </div>
                            <div class="mt-4 pt-3 border-top text-muted small d-flex justify-content-between">
                                <span>اضغط للدخول للمراقبة</span>
                                <div>
                                    <span class="badge bg-primary rounded-circle p-1"></span>
                                    <span class="badge bg-primary opacity-75 rounded-circle p-1"></span>
                                    <span class="badge bg-primary opacity-50 rounded-circle p-1"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center p-5 border rounded-4 bg-white shadow-sm">
                        <i class="fas fa-layer-group fa-3x text-warning mb-3"></i>
                        <h5 class="fw-bold">لا توجد صفوف</h5>
                        <p class="text-muted">لم يتم ربط هذا الاختبار بأي صفوف بعد</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection