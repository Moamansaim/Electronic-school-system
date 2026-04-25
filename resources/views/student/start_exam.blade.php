@extends('layout-cms.main-layout')

@section('content')
    <div class="container py-5 bg-light min-vh-100" dir="rtl">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white p-3 rounded-circle shadow">
                            <i class="bi bi-pencil-square fs-3"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $exam->subject->name ?? 'الاختبار العام' }}</h3>
                            <small class="text-muted">يرجى الإجابة على جميع الأسئلة</small>
                        </div>
                    </div>

                    <div class="timer-container p-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-hourglass-split fs-4 text-dark pulse-icon"></i>
                            <div>
                                <small class="text-muted">الوقت المتبقي</small>
                                <div id="exam-timer" class="fw-bold fs-4 text-dark">00:00:00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="progress mb-4" style="height: 8px;">
                    <div id="progress-bar" class="progress-bar bg-success" style="width:0%"></div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 mb-4 sticky-top nav-wrapper">
                    <div class="card-body text-center">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            @foreach ($questions as $index => $question)
                                <button type="button"
                                    class="btn nav-btn {{ $index == 0 ? 'active' : '' }} {{ isset($student_answers[$question->id]) ? 'answered' : '' }}"
                                    onclick="showQuestion({{ $index }})" id="nav-{{ $index }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <form id="examForm" action="{{ route('exams.submit_exam') }}" method="POST">
                    @csrf
                    <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">

                    @foreach ($questions as $index => $question)
                        <div class="question-card {{ $index != 0 ? 'd-none' : '' }}" id="q-section-{{ $index }}">
                            <div class="card shadow-sm border-0 rounded-4 mb-4">
                                <div class="card-header bg-white">
                                    <span class="badge bg-primary">السؤال {{ $index + 1 }}</span>
                                    <h5 class="mt-2">{{ $question->question_text }}</h5>
                                </div>

                                <div class="card-body">
                                    @if ($question->question_type == 'multiple_choice')
                                        @foreach ($question->options as $option)
                                            <div class="mb-3">
                                                <input type="radio" name="answer[{{ $question->id }}]" value="{{ $option->id }}"
                                                    onchange="autoSave('{{ $question->id }}', this.value, {{ $index }})" {{-- التحقق من
                                                    التطابق مع الـ ID المحفوظ --}} @if(isset($student_answers[$question->id]) && $student_answers[$question->id] == $option->id) checked @endif>
                                                {{ $option->option_text }}
                                            </div>
                                        @endforeach

                                    @else
                                      <div class="alert alert-warning text-center w-100">لا يوجد أسئلة!</div>
                                    @endif
                                </div>

                                <div class="card-footer d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" onclick="showQuestion({{ $index - 1 }})" {{ $index == 0 ? 'disabled' : '' }}>السابق</button>
                                    @if ($index < count($questions) - 1)
                                        <button type="button" class="btn btn-primary"
                                            onclick="showQuestion({{ $index + 1 }})">التالي</button>
                                    @else
                                        <button type="button" onclick="confirmSubmit()" class="btn btn-success">إنهاء</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">
                <h4>تأكيد التسليم؟</h4>
                <button class="btn btn-success" onclick="submitFinalForm()">تأكيد</button>
            </div>
        </div>
    </div>

    <style>
        .timer-container {
            background: white;
            border-radius: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: 900;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .nav-btn {
            width: 45px;
            height: 45px;
            border: 1px solid #ddd;
        }

        .nav-btn.active {
            background: #0d6efd;
            color: white;
        }

        .nav-btn.answered {
            background: #198754;
            color: white;
        }

        .pulse-icon {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            50% {
                opacity: 0.5;
            }
        }
    </style>

    <script>
        let totalQuestions = {{ count($questions) }};
        function showQuestion(index) {
            if (index < 0 || index >= totalQuestions) return;
            document.querySelectorAll('.question-card').forEach(q => q.classList.add('d-none'));
            document.getElementById(`q-section-${index}`).classList.remove('d-none');
            document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`nav-${index}`).classList.add('active');
        }

        function autoSave(questionId, value, index) {
            if (!value) return;
            let btn = document.getElementById(`nav-${index}`);
            btn.classList.add('answered');

            fetch("{{ route('auto_save.answers') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ question_id: questionId, answer: value, attempt_id: "{{ $attempt->id }}" })
            })
                .then(res => res.json())
                .then(data => { updateProgress(); })
                .catch(err => { btn.classList.remove('answered'); console.error(err); });
        }

        function updateProgress() {
            let answeredCount = document.querySelectorAll('.nav-btn.answered').length;
            let percent = (answeredCount / totalQuestions) * 100;
            document.getElementById('progress-bar').style.width = percent + '%';
        }

        function initTimer() {
            const endTime = new Date("{{ $endTime }}".replace(" ", "T")).getTime();
            setInterval(() => {
                let now = new Date().getTime();
                let diff = endTime - now;
                if (diff <= 0) { submitFinalForm(); return; }
                let h = Math.floor(diff / 3600000);
                let m = Math.floor((diff % 3600000) / 60000);
                let s = Math.floor((diff % 60000) / 1000);
                document.getElementById('exam-timer').innerHTML = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }, 1000);
        }

        function confirmSubmit() { new bootstrap.Modal(document.getElementById('confirmModal')).show(); }
        function submitFinalForm() { document.getElementById('examForm').submit(); }

        document.addEventListener('DOMContentLoaded', function () {
            initTimer();
            updateProgress();
        });
    </script>
@endsection