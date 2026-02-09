@extends('layout-cms.main-layout')
@section('title', 'جدول الحصص المدرسي')

@section('content')
    <div class="container-fluid p-4">
        <x-grade-level-success-component />
        <x-grade-level-error-component />
        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col ">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <I class="fas fa-calendar-check text-primary ml-2"></i> الجدول الزمني لحصص المعلم :
                            {{ $teacher->full_name }}
                        </h4>
                    </div>
                </div>
            </div>

            @php
                $custom_order = ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'];
                $custom_order_class_schedule = ['الحصة الأولى', 'الحصة الثانية', 'الحصة الثالثة', 'الحصة الرابعة', 'الحصة الخامسة', 'الحصة السادسة'];

                $existing_days = $class_schedules->pluck('day')->unique()->toArray();
                $days = array_intersect($custom_order, $existing_days);

                $existing_class_schedules = $class_schedules->pluck('class_schedule')->unique()->toArray();
                $period_names = array_intersect($custom_order_class_schedule, $existing_class_schedules);
            @endphp

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered timetable-grid mb-0">
                        <thead>
                            <tr>
                                <th class="bg-light">الحصة / اليوم</th>
                                @foreach ($days as $day)
                                    <th class="period-header">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($period_names as $period_name)
                                <tr>
                                    <td class="day-name-cell">{{ $period_name }}</td>

                                    @foreach($days as $day)
                                        <td class="slot-cell">
                                            @php
                                                $session = $class_schedules->where('day', $day)
                                                    ->where('class_schedule', $period_name)
                                                    ->first();
                                            @endphp

                                            @if($session)
                                                <div class="session-box">
                                                    <div class="classroom-info mb-2">
                                                        <span class="badge badge-primary-soft">
                                                            <I class="fas fa-door-open ml-1"></i>
                                                            {{ $session->classroom->name ?? 'صف: ' . $session->classroom_id }}
                                                        </span>
                                                    </div>

                                                    <div class="action-buttons">
                                                        <a href="{{ route('class-schedules.edit', [$session->id , $teacher->id]) }}"
                                                            Class="btn btn-sm btn-light text-info border" title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <button Class="btn btn-sm btn-light text-danger border" title="حذف"
                                                            data-toggle="modal" data-target="#deleteModal{{ $session->id}}">
                                                            <i class="fas fa-trash-alt "></i>
                                                        </button>

                                                        <div class="modal fade" id="deleteModal{{ $session->id}}" tabindex="-1"
                                                            role="dialog">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                                                    <div class="modal-body p-5 text-center">
                                                                        <div class="text-danger mb-4">
                                                                            <i class="fas fa-exclamation-circle fa-4x"></i>
                                                                        </div>
                                                                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                                                                        <p class="text-muted">هل أنت متأكد من حذف
                                                                            <strong>
                                                                                {{ $period_name . "/" . $day  }}
                                                                            </strong>؟<br>هذا الإجراء لا
                                                                            يمكن التراجع عنه.
                                                                        </p>
                                                                        <div class="d-flex justify-content-center mt-4">
                                                                            <button type="button"
                                                                                class="btn btn-light px-4 mr-2 rounded-pill"
                                                                                data-dismiss="modal">إلغاء</button>
                                                                            <form
                                                                                action="{{ route('class-schedules.destroy', $session->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-danger px-4 rounded-pill">تأكيد
                                                                                    الحذف</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <form id="delete-form-{{ $session->id }}"
                                                            Action="{{ route('class-schedules.destroy', $session->id) }}" Method="POST"
                                                            style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="empty-slot">---</div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-center">
                                        <img src=https://cdn-icons-png.flaticon.com/512/7486/7486744.png width="80"
                                            class="mb-3 opacity-50">
                                        <p class="text-muted">لم يتم العثور على أي حصص لهذا المعلم حالياً.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




@endsection