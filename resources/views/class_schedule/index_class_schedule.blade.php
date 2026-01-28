@extends('layout-cms.main-layout')
@section('title', 'جدول الحصص المدرسي')

@section('content')
    <div class="container-fluid p-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-right">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-calendar-check text-primary ml-2"></i> الجدول الزمني للحصص
                        </h4>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered timetable-grid mb-0">
                        <thead>
                            <tr>
                                <th class="day-header">اليوم</th>
                                @foreach($class_schedules->pluck('class_schedule')->unique()->sort() as $periodName)
                                    <th class="period-header">{{ $periodName }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $days = $class_schedules->pluck('day')->unique();
                                $periods = $class_schedules->pluck('class_schedule')->unique()->sort();
                            @endphp

                            @foreach($days as $day)
                                <tr>
                                    <td class="day-name-cell">{{ $day }}</td>
                                    
                                    @foreach($periods as $period)
                                        <td class="slot-cell">
                                            @php
                                                $session = $class_schedules->where('day', $day)
                                                                           ->where('class_schedule', $period)
                                                                           ->first();
                                            @endphp

                                            @if($session)
                                                <div class="session-box">
                                                    {{-- اسم المعلم عبر علاقة الـ Foreign Key --}}
                                                    <div class="teacher-info">
                                                        <i class="fas fa-user-tie mb-1"></i>
                                                        <span>{{ $session->teacher->full_name ?? 'معلم: ' . $session->teacher_id }}</span>
                                                    </div>
                                                    
                                                    <div class="classroom-info">
                                                        {{-- اسم الصف عبر علاقة الـ Foreign Key --}}
                                                        <span class="badge badge-primary-soft">
                                                            <i class="fas fa-door-open ml-1"></i>
                                                            {{ $session->classroom->name ?? 'صف: ' . $session->classroom_id }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="empty-slot">---</div>
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

    <style>
        /* تنسيقات الجدول المدرسي الاحترافي */
        .timetable-grid {
            text-align: center;
            border: 1px solid #ebedef !important;
        }

        /* ترويسة الجدول - كحلي */
        .timetable-grid thead th {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 20px 10px;
            font-weight: 600;
            border: 1px solid #3e4f5f !important;
            vertical-align: middle;
        }

        /* عمود الأيام - أزرق */
        .day-name-cell {
            background-color: #f8fafc;
            font-weight: bold;
            color: #2c3e50;
            border-left: 5px solid #3498db !important; /* الهوية الزرقاء */
            vertical-align: middle !important;
            min-width: 120px;
        }

        .slot-cell {
            height: 120px;
            min-width: 160px;
            padding: 10px !important;
            vertical-align: middle !important;
            background-color: #ffffff;
            transition: all 0.2s;
        }

        .slot-cell:hover {
            background-color: #f1f7fd;
        }

        /* صندوق الحصة */
        .session-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .teacher-info {
            font-size: 0.95rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .teacher-info i {
            display: block;
            color: #3498db;
            font-size: 1.1rem;
        }

        .badge-primary-soft {
            background-color: rgba(52, 152, 219, 0.1);
            color: #3498db;
            border: 1px solid rgba(52, 152, 219, 0.2);
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        .empty-slot {
            color: #d1d8e0;
            font-size: 0.8rem;
        }

        /* لضمان تناسق الخط */
        body { font-family: 'Cairo', sans-serif; }
    </style>
@endsection
