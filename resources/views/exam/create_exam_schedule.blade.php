@extends('layout-cms.main-layout')
@section('title', 'نشر جدول امتحانات')

@section('content')
    <div class="p-4">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary font-weight-bold">
                    <i class="fas fa-calendar-alt mr-2"></i> إعداد ونشر جدول امتحانات
                </h5>
            </div>

            <form action="{{ route('exam-schedules.store') }}" method="POST">
                @csrf
                <div class="card-body p-5">
                    <div class="row">
                        <!-- عنوان الجدول -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label font-weight-bold mb-2">عنوان الجدول</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i class="fas fa-heading text-muted"></i></span>
                                </div>
                                <input type="text" name="schedule_title" value="{{ old('schedule_title') }}"
                                    class="form-control @error('schedule_title') is-invalid @enderror"
                                    placeholder="مثال: جدول الاختبارات النهائية للفصل الأول">
                            </div>
                            @error('schedule_title') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- المادة الدراسية -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">المادة الدراسية</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i class="fas fa-book text-muted"></i></span>
                                </div>
                                <select name="subject_id" class="form-control @error('subject_id') is-invalid @enderror">
                                    <option value="" selected disabled>اختر المادة...</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('subject_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                       

                        <!-- التاريخ واليوم -->
                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold mb-2">تاريخ الامتحان</label>
                            <input type="date" name="exam_date" id="exam_date" value="{{ old('exam_date') }}"
                                class="form-control @error('exam_date') is-invalid @enderror">
                            @error('exam_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col mb-4">
                            <label class="form-label font-weight-bold mb-2">اليوم</label>
                            <input type="text" name="exam_day" id="exam_day" value="{{ old('exam_day') }}"
                                class="form-control @error('exam_day') is-invalid @enderror" readonly placeholder="سيحدد تلقائياً">
                        </div>

                        <!-- التوقيت -->
                        <div class="col mb-4">
                            <label class="form-label font-weight-bold mb-2">وقت البدء</label>
                            <input type="time" name="start_time" value="{{ old('start_time') }}"
                                class="form-control @error('start_time') is-invalid @enderror">
                        </div>

                        <div class="col mb-4">
                            <label class="form-label font-weight-bold mb-2">وقت الانتهاء</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}"
                                class="form-control @error('end_time') is-invalid @enderror">
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('classrooms.index') }}" class="text-muted">
                        <i class="fas fa-arrow-right mr-1"></i> العودة
                    </a>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-bullhorn ml-2"></i> نشر الجدول الآن
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // سكربت بسيط لتحديد اليوم تلقائياً عند اختيار التاريخ
        document.getElementById('exam_date').addEventListener('change', function() {
            const date = new Date(this.value);
            const options = { weekday: 'long' };
            document.getElementById('exam_day').value = new Intl.DateTimeFormat('ar-EG', options).format(date);
        });
    </script>
@endsection