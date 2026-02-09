@extends('layout-cms.main-layout')
@section('title', 'إضافة الأسئلة للإختبار')

@section('content')
    <div class="p-4">
        <x-grade-level-error-component />

        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary font-weight-bold">
                    <i class="fas fa-question-circle mr-2"></i> إضافة أسئلة للاختبار:
                    {{ $exam->subject->name ?? 'عام' }}
                    </h5>
                <p class="text-muted small mb-0 mt-1">أنت الآن تضيف أسئلة للاختبار الخاص بـ:
                    <strong>{{ $exam->name }}</strong>
                </p>
                </div>

            <form action="{{ route('questions.store') }}" method="POST" id="question_form">
                @csrf
                
                {{-- حقل ID الاختبار المخفي لجلب البيانات بشكل صحيح --}}
                <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                <div class="card-body p-5">
                    <div class="row">
                        {{-- نص السؤال --}}
                        <div class="col-md-12 mb-4">
                            <label class="form-label font-weight-bold mb-2">نص السؤال</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span
                                        class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-pen text-muted"></i>
                                        </span>
                                    </div>
                                <input type="text" name="question_text" maxlength="300"
                                    value="{{ old('question_text') }}"
                                    class="form-control border-left-0 @error('question_text') is-invalid @enderror"
                                    placeholder="أدخل نص السؤال هنا..." required>
                                </div>
                            @error('question_text')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            
                        </div>

                        {{-- نوع السؤال --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">نوع السؤال</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span
                                        class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-list text-muted"></i>
                                        </span>
                                    </div>
                                <select name="question_type" id="question_type"
                                    class="form-control border-left-0 @error('question_type') is-invalid @enderror">
                                    <option value="essay_question"
                                        {{ old('question_type') == 'essay_question' ? 'selected' : '' }}>سؤال مقالي (Essay)
                                    </option>
                                    <option value="multiple_choice"
                                        {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>اختيار من متعدد
                                        (MCQ)
                                    </option>
                                    </select>
                                </div>
                            @error('question_type')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            
                        </div>

                        {{-- درجة السؤال --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">درجة السؤال
                                (Mark)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span
                                        class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-star text-muted"></i>
                                        </span>
                                    </div>
                                <input type="number" name="mark"
                                    value="{{ old('mark') }}"
                                    class="form-control border-left-0 @error('mark') is-invalid @enderror" required>
                                </div>
                            @error('mark')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                            
                        </div>
                        </div>

                    {{-- قسم خيارات الإجابة (يظهر فقط عند اختيار MCQ) --}}
                    <div id="options_wrapper" style="display: none;" class="mt-3">
                        <div class="card border-primary mb-4"
                            style="border-radius: 10px; border-style: dashed;">
                            <div class="card-body bg-light">
                                <h6 class="font-weight-bold mb-3 text-primary">
                                    <i class="fas fa-tasks ml-1"></i> خيارات الإجابة
                                    </h6>
                                <div class="row">
                                    @for ($i = 1; $i <= 4; $i++)
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group shadow-sm">
                                                <div
                                                    class="input-group-prepend">
                                                    <div
                                                        class="input-group-text bg-white border-right-0">
                                                        <input
                                                            type="radio" name="is_correct" value="{{ $i - 1 }}"
                                                            
                                                            {{ old('is_correct') == $i - 1 ? 'checked' : ($i == 1 ? 'checked' : '') }}
                                                            
                                                            title="حدد كإجابة صحيحة">
                                                        </div>
                                                    </div>
                                                <input type="text"
                                                    name="options[]" class="form-control border-left-0"
                                                    placeholder="نص الخيار {{ $i }}"
                                                    value="{{ old('options.' . ($i - 1)) }}">
                                                </div>
                                            </div>
                                    @endfor
                                    </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle ml-1"></i>
                                        ملاحظة: اكتب نص الخيارات في الحقول أعلاه،
                                        وقم بتحديد الدائرة بجانب الخيار الذي يمثل الإجابة الصحيحة.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div
                        class="card-footer bg-white border-0 px-0 d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('exams.index') }}" class="text-muted">
                            <i class="fas fa-arrow-right mr-1"></i> العودة للاختبارات
                            </a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm"
                            style="border-radius: 8px;">
                            <i class="fas fa-save ml-2"></i> حفظ السؤال
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @push('script')
        <script>
            $(document).ready(function() {
                function handleQuestionTypeChange() {
                    var selectedType = $('#question_type').val();
                    var $optionsWrapper = $('#options_wrapper');
                    var $optionInputs = $('input[name="options[]"]');

                    if (selectedType === 'multiple_choice') {
                        $optionsWrapper.slideDown();
                        $optionInputs.prop('required', true);
                    } else {
                        $optionsWrapper.slideUp();
                        $optionInputs.prop('required', false);
                    }
                }

                // التنفيذ عند تغيير القائمة
                $('#question_type').on('change', function() {
                    handleQuestionTypeChange();
                });

                // التنفيذ عند تحميل الصفحة (لمعالجة حالة إعادة التوجيه بعد خطأ التحقق)
                handleQuestionTypeChange();
            });
        </script>
    @endpush
@endsection ها هي صفحة الاضافة اريد صفحة التعديل
