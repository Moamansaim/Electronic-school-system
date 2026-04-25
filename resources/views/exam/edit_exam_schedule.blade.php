@extends('layout-cms.main-layout')
@section('title', 'تعديل موعد اختبار')

@section('content')
<div class="p-4">
    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-success font-weight-bold">
                <i class="fas fa-edit mr-2"></i> تعديل موعد: {{ $examSchedule->subject->name }}
            </h5>
        </div>

        <form action="{{ route('exam-schedules.update', $examSchedule->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body p-5">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="font-weight-bold mb-2">عنوان الجدول</label>
                        <input type="text" name="schedule_title" value="{{ old('schedule_title', $examSchedule->schedule_title) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="font-weight-bold mb-2">المادة الدراسية</label>
                        <select name="subject_id" class="form-control">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $examSchedule->subject_id == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="font-weight-bold mb-2">نوع الامتحان</label>
                        <select name="exam_type" class="form-control">
                            <option value="midterm" {{ $examSchedule->exam_type == 'midterm' ? 'selected' : '' }}>نصفي</option>
                            <option value="final" {{ $examSchedule->exam_type == 'final' ? 'selected' : '' }}>نهائي</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="font-weight-bold mb-2">التاريخ</label>
                        <input type="date" name="exam_date" id="exam_date" value="{{ $examSchedule->exam_date }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="font-weight-bold mb-2">اليوم</label>
                        <input type="text" name="exam_day" id="exam_day" value="{{ $examSchedule->exam_day }}" class="form-control" readonly>
                    </div>

                    <div class="col-md-2 mb-4">
                        <label class="font-weight-bold mb-2">البدء</label>
                        <input type="time" name="start_time" value="{{ $examSchedule->start_time }}" class="form-control">
                    </div>

                    <div class="col-md-2 mb-4">
                        <label class="font-weight-bold mb-2">الانتهاء</label>
                        <input type="time" name="end_time" value="{{ $examSchedule->end_time }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light py-3 d-flex justify-content-between">
                <a href="{{ route('exam-schedules.index') }}" class="btn btn-light">إلغاء</a>
                <button type="submit" class="btn btn-success px-5">تحديث البيانات</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('exam_date').addEventListener('change', function() {
        const date = new Date(this.value);
        const options = { weekday: 'long' };
        document.getElementById('exam_day').value = new Intl.DateTimeFormat('ar-EG', options).format(date);
    });
</script>
@endsection