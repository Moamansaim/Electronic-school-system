
@extends('layout-cms.main-layout')
@section('title', 'نشر جدول امتحانات')

@section('content')
<div class="p-4">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(!$exam_schedule_title)
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>ملاحظة هامة:</strong> يجب إضافة عنوان للاختبار أولاً وحفظه حتى تتمكن من البدء بإضافة تفاصيل المواد والمواعيد.
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-primary font-weight-bold">
                <i class="fas fa-calendar-alt mr-2"></i> إعداد ونشر جدول امتحانات
            </h5>
        </div>
        <div class="card-body p-5">
            {{-- فورم العنوان --}}
            <form action="{{ route('exam-schedules.store') }}" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-9 mb-4">
                        <label class="form-label font-weight-bold mb-2">عنوان الجدول (يمكنك تعديل العنوان بالكتابة مباشرة في الحقل وسيتم التعديل تلقائياً)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-muted"></i></span>
                            </div>
                            <input type="text" id="schedule_title" name="schedule_title"
                                value="{{ $exam_schedule_title->schedule_title ?? old('schedule_title') }}"
                                class="form-control @error('schedule_title') is-invalid @enderror"
                                placeholder="مثال: جدول الاختبارات النهائية 2026">
                        </div>
                        @error('schedule_title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-4">
                        @if ($exam_schedule_title)
                            {{-- لاحظ تم تغيير نوع الزر إلى type="button" لمنع عمل Submit للفورم --}}
                            <button type="button" data-toggle="modal" data-target="#deleteModal{{ $exam_schedule_title->id }}"
                                class="btn btn-danger btn-block shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-trash-alt ml-1"></i> حذف العنوان
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary btn-block shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-save ml-1"></i> حفظ العنوان
                            </button>
                        @endif
                    </div>
                </div>
            </form>

            <hr class="my-5" style="border-top: 2px dashed #e9ecef;">

            {{-- فورم تفاصيل المواد --}}
            <form action="{{ route('data_exam_schedules.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="form-label font-weight-bold mb-2 text-muted">تابع لجدول:</label>
                        <input type="text" class="form-control bg-light"
                            value="{{ $exam_schedule_title->schedule_title ?? 'لم يتم تحديد عنوان بعد' }}" readonly>
                        <input type="hidden" name="exam_schedule_id" value="{{ $exam_schedule_title->id ?? '' }}">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label font-weight-bold mb-2">المادة الدراسية</label>
                        <select name="subject_id" class="form-control @error('subject_id') is-invalid @enderror" {{ !$exam_schedule_title ? 'disabled' : '' }}>
                            <option value="" selected disabled>اختر المادة...</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-4">
                        <label class="form-label font-weight-bold mb-2">تاريخ الامتحان</label>
                        <input type="date" name="exam_date" id="exam_date" value="{{ old('exam_date') }}"
                            class="form-control @error('exam_date') is-invalid @enderror" {{ !$exam_schedule_title ? 'disabled' : '' }}>
                    </div>

                    <div class="col-md-3 mb-4">
                        <label class="form-label font-weight-bold mb-2">اليوم</label>
                        <input type="text" name="exam_day" id="exam_day" class="form-control bg-light" readonly
                            placeholder="تلقائي">
                    </div>

                    <div class="col-md-3 mb-4">
                        <label class="form-label font-weight-bold mb-2">وقت البدء</label>
                        <input type="time" name="start_time" class="form-control" {{ !$exam_schedule_title ? 'disabled' : '' }}>
                    </div>

                    <div class="col-md-3 mb-4">
                        <label class="form-label font-weight-bold mb-2">وقت الانتهاء</label>
                        <input type="time" name="end_time" class="form-control" {{ !$exam_schedule_title ? 'disabled' : '' }}>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-5 shadow-sm" style="border-radius: 8px;" {{ !$exam_schedule_title ? 'disabled' : '' }}>
                        <i class="fas fa-plus-circle ml-2"></i> حفظ بيانات المادة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- المودال خارج الفورم لضمان عدم تداخل الأكشن --}}
@if($exam_schedule_title)
<div class="modal fade" id="deleteModal{{ $exam_schedule_title->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
            <div class="modal-body p-5 text-center">
                <div class="text-danger mb-4">
                    <i class="fas fa-exclamation-circle fa-4x"></i>
                </div>
                <h3 class="font-weight-bold">تأكيد الحذف</h3>
                <p class="text-muted">هل أنت متأكد من حذف
                    <strong>({{ $exam_schedule_title->schedule_title}})</strong>؟<br>هذا الإجراء لا
                    يمكن التراجع عنه.
                </p>
                <div class="d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-light px-4 mx-2 rounded-pill"
                        data-dismiss="modal">إلغاء</button>
                    <form action="{{ route('exam-schedules.destroy', $exam_schedule_title->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 rounded-pill">تأكيد الحذف</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .is-updated { border-color: #28a745 !important; background-color: #d4edda !important; transition: 0.5s; }
    .is-error { border-color: #dc3545 !important; background-color: #f8d7da !important; transition: 0.5s; }
</style>

<script>
    function notify(message, isSuccess = true) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: isSuccess ? 'success' : 'error',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: isSuccess ? '#28a745' : '#dc3545',
                color: '#fff'
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // 1. منطق اليوم
        const dateInp = document.getElementById('exam_date');
        const dayInp = document.getElementById('exam_day');

        if (dateInp) {
            dateInp.addEventListener('change', function () {
                if (this.value) {
                    const date = new Date(this.value);
                    const dayName = new Intl.DateTimeFormat('ar-EG', { weekday: 'long' }).format(date);
                    dayInp.value = dayName;
                }
            });
        }

        // 2. تحديث العنوان (AJAX)
        const titleInp = document.getElementById('schedule_title');
        const scheduleId = "{{ $exam_schedule_title->id ?? '' }}";

        if (titleInp && scheduleId) {
            titleInp.addEventListener('change', function () {
                const newVal = this.value;

                fetch(`/cms/exam-schedules/${scheduleId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'PUT', schedule_title: newVal })
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        let errorMsg = data.message || 'فشل التحديث';
                        if (data.errors) errorMsg = Object.values(data.errors).flat()[0];
                        throw new Error(errorMsg);
                    }
                    return data;
                })
                .then(data => {
                    titleInp.classList.add('is-updated');
                    notify('تم تحديث العنوان بنجاح');
                    setTimeout(() => titleInp.classList.remove('is-updated'), 2000);
                })
                .catch(err => {
                    titleInp.classList.add('is-error');
                    notify(err.message, false);
                    setTimeout(() => titleInp.classList.remove('is-error'), 2000);
                });
            });
        }
    });
</script>
@endsection
