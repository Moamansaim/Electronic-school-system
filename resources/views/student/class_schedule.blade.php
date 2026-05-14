@extends('layout-cms.main-layout')
@section('title', 'جدول الحصص المدرسي')

@section('content')
    <div class="container-fluid p-4">
            <div class="card-body py-3">
                <div class="row align-items-center">
                  
                    <div class="col-md-4 text-left">
                        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="fas fa-print ml-1"></i> طباعة الجدول
                        </button>
                    </div>
                </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center" style="width: 100%; border: 2px solid #dee2e6;">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 140px; vertical-align: middle; background-color: #007bff !important;"
                                    class="py-3">اليوم / الحصة</th>
                                @php
                                    $daysOrder = ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'];
                                    $periods = ['الحصة الأولى', 'الحصة الثانية', 'الحصة الثالثة', 'الحصة الرابعة', 'الحصة الخامسة', 'الحصة السادسة'];
                                @endphp
                                @foreach ($periods as $period)
                                    <th class="py-3" style="vertical-align: middle;">{{ $period }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daysOrder as $day)
                                <tr>
                                    <td class="bg-light font-weight-bold align-middle text-dark"
                                        style="font-size: 1.1rem; border-left: 2px solid #dee2e6;">
                                        {{ $day }}
                                    </td>

                                    @foreach($periods as $period)
                                        <td class="align-middle p-3" style="height: 110px; min-width: 130px;">
                                            @php
                                                // البحث عن الحصة لهذا اليوم المحدد
                                                $session = isset($groupedSchedule[$day])
                                                    ? $groupedSchedule[$day]->where('class_schedule', $period)->first()
                                                    : null;

                                                $assignment = $session?->teacher?->teacherAssignments?->first();
                                            @endphp

                                            @if($session && $assignment)
                                                <div class="d-flex flex-column justify-content-center h-100">
                                                    <span class="text-primary font-weight-bold mb-1"
                                                        style="font-size: 1.1rem; line-height: 1.2;">
                                                        {{ $assignment->subject->name }}
                                                    </span>
                                                    <span class="text-secondary small">
                                                        أ. {{ $session->teacher->full_name }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="text-muted opacity-25">-</div>
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
        .table {
            border-collapse: collapse !important;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6 !important;
            vertical-align: middle !important;
        }

        .bg-primary {
            background-color: #007bff !important;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .btn,
            .sidebar,
            .navbar,
            .footer,
            .layout-footer,
            .d-print-none {
                display: none !important;
            }

            .table {
                width: 100% !important;
                font-size: 12px !important;
            }

            .table thead th {
                background-color: #007bff !important;
                color: white !important;
                border: 1px solid #333 !important;
            }

            .table-bordered td,
            .table-bordered th {
                border: 1px solid #333 !important;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
@endsection