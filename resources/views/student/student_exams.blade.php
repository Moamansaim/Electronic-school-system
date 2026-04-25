
@extends('layout-cms.main-layout')
@section('title', 'صفحة الاختبارات')

@section('content')
<div class="container py-5 text-end" dir="rtl">
    <x-grade-level-error-component />
    
    @if($exams->isEmpty())
        <div class="alert alert-warning shadow-sm">لا توجد امتحانات منشورة لهذا الصف حالياً.</div>
    @else
        <div class="row">
            @foreach($exams as $exam)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow border-0 rounded-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title mb-0 text-center fw-bold">{{ $exam->subject->name }}</h5>
                        </div>

                        <div class="card-body p-4">
                            @php
                                // تحسين: جلب المحاولة الأولى المحملة مسبقاً
                                $userAttempt = $exam->attempts->first();
                            @endphp

                            @if($userAttempt && $userAttempt->status === "submitted")
                                <div class="alert alert-success text-center">تم تسليم الاختبار بنجاح</div>
                            @else
                                <div class="mb-3 text-center">
                                    <small class="text-muted d-block">موعد البدء</small>
                                    <span class="fw-bold">
                                        {{ \Carbon\Carbon::parse($exam->pivot->start_time)->format('Y-m-d h:i A') }}
                                    </span>
                                </div>

                                {{-- إضافة attributes للتعرف عليها عبر JS --}}
                                <div class="countdown-box p-3 mb-4 rounded-3 text-center bg-light border exam-timer-container" 
                                     data-start="{{ $exam->pivot->start_time }}" 
                                     data-end="{{ $exam->pivot->end_time }}" 
                                     data-id="{{ $exam->id }}">
                                    
                                    <p id="label-{{ $exam->id }}" class="small fw-bold text-secondary mb-1">يبدأ خلال:</p>
                                    <div id="timer-{{ $exam->id }}" class="h3 fw-bold text-dark font-monospace mb-0">
                                        00:00:00
                                    </div>
                                </div>

                                <form action="{{ route('exams.start', $exam->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" id="btn-{{ $exam->id }}" class="btn btn-lg w-100 py-2 fw-bold rounded-pill btn-secondary disabled">
                                        قيد الانتظار...
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- تحسين: وضع السكريبت مرة واحدة خارج الـ Loop --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const timerContainers = document.querySelectorAll('.exam-timer-container');

        const interval = setInterval(function () {
            let allFinished = true;

            timerContainers.forEach(container => {
                const id = container.dataset.id;
                const start = new Date(container.dataset.start).getTime();
                const end = new Date(container.dataset.end).getTime();
                
                const timer = document.getElementById(`timer-${id}`);
                const label = document.getElementById(`label-${id}`);
                const btn = document.getElementById(`btn-${id}`);

                if(!timer || !btn) return;

                const now = new Date().getTime();
                const toStart = start - now;
                const toEnd = end - now;

                if (toStart > 0) {
                    updateUI(timer, toStart);
                    allFinished = false;
                } else if (toEnd > 0) {
                    label.innerText = "الاختبار متاح الآن! ينتهي خلال:";
                    label.className = "small fw-bold text-success mb-1";
                    timer.className = "h3 fw-bold text-success font-monospace mb-0";
                    btn.className = "btn btn-lg w-100 py-2 fw-bold rounded-pill btn-success";
                    btn.innerText = "دخول الامتحان";
                    updateUI(timer, toEnd);
                    allFinished = false;
                } else {
                    label.innerText = "انتهى وقت التقديم";
                    timer.innerText = "00:00:00";
                    timer.className = "h3 fw-bold text-danger font-monospace mb-0";
                    btn.className = "btn btn-lg w-100 py-2 fw-bold rounded-pill btn-danger disabled";
                    btn.innerText = "انتهى الوقت";
                    btn.disabled = true;
                }
            });

            // تحسين: إذا انتهت كل التوقيتات، توقف عن التحديث لتوفير موارد الجهاز
            if (allFinished) clearInterval(interval);
        }, 1000);

        function updateUI(element, dist) {
            const h = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((dist % (1000 * 60)) / 1000);
            element.innerHTML = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }
    });
</script>
@endsection
