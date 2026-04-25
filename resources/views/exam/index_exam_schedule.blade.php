@extends('layout-cms.main-layout')
@section('title', 'عرض جدول الاختبارات')

@section('content')
    <div class="container-fluid p-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header border-0 py-4 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 font-weight-bold">
                    <i class="fas fa-calendar-alt text-primary mr-2"></i> جدول الاختبارات المنشورة
                </h4>
                <a href="{{ route('exam-schedules.create') }}" class="btn btn-primary text-white shadow-sm px-4 rounded-pill">
                    <i class="fas fa-plus mr-1 text-white"></i> إضافة موعد جديد
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive" dir="rtl">
                    <table class="table text-center table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3">العنوان</th>
                                <th class="py-3">المادة</th>
                                <th class="py-3">اليوم والتاريخ</th>
                                <th class="py-3">التوقيت</th>
                                <th class="py-3">النوع</th>
                                <th class="py-3">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                                <tr>
                                    <td class="font-weight-bold">{{ $schedule->schedule_title }}</td>
                                    <td>{{ $schedule->subject->name }}</td>
                                    <td>
                                        <span class="d-block">{{ $schedule->exam_day }}</span>
                                        <small class="text-muted">{{ $schedule->exam_date }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">من: {{ $schedule->start_time }}</span>
                                        <span class="badge badge-light">إلى: {{ $schedule->end_time }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $schedule->exam_type == 'final' ? 'badge-danger' : 'badge-info' }}">
                                            {{ $schedule->exam_type == 'final' ? 'نهائي' : 'نصفي' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('exam-schedules.edit', $schedule->id) }}"
                                                class="btn btn-sm btn-outline-success mx-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('exam-schedules.destroy', $schedule->id) }}" method="POST"
                                                onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-5 text-center">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50">
                                        <p class="text-muted">لم يتم العثور على أي جداول اختبارات حالياً.</p>
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