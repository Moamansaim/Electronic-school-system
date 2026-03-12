@extends('layout-cms.main-layout')
@section('title', 'إضافة الأسئلة للإختبار')

@section('content')
    <div class="p-4">
        <x-grade-level-error-component />
        <x-grade-level-success-component />
        
        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary font-weight-bold">
                    <i class="fas fa-question-circle mr-2"></i> إضافة أسئلة للاختبار: {{ $exam->subject->name ?? 'عام' }}
                </h5>
            </div>

            <form action="{{ route('questions.store') }}" method="POST" id="question_form">
                @csrf
                <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label font-weight-bold">نص السؤال</label>
                            <input type="text" name="question_text" class="form-control" required value="{{ old('question_text') }}">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold">نوع السؤال</label>
                            <select name="question_type" id="question_type" class="form-control">
                                <option value="essay_question" {{ old('question_type') == 'essay_question' ? 'selected' : '' }}>سؤال مقالي</option>
                                <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>اختيار من متعدد (MCQ)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold">الدرجة</label>
                            <input type="number" name="mark" class="form-control" required value="{{ old('mark') }}">
                        </div>
                    </div>

                    {{-- قسم الخيارات الديناميكي --}}
                    <div id="options_wrapper" style="display: none;" class="mt-3">
                        <div class="card border-primary mb-4" style="border-radius: 10px; border-style: dashed;">
                            <div class="card-body bg-light">
                                <h6 class="font-weight-bold mb-3 text-primary">خيارات الإجابة</h6>
                                <div id="options_container">
                                    {{-- سيتم إضافة الحقول هنا --}}
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="addOption()">
                                    <i class="fas fa-plus"></i> إضافة خيار جديد
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 px-0 d-flex justify-content-between mt-4">
                        <a href="{{ route('exams.index') }}" class="text-muted"><i class="fas fa-arrow-right ml-1"></i> العودة</a>
                        <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save ml-2"></i> حفظ السؤال</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('script')
    <script>
        let optionCount = 0;

        function addOption() {
            optionCount++;
            let html = `
                <div class="row mb-2 option-row align-items-center">
                    <div class="col-md-10">
                        <div class="input-group shadow-sm">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-white">
                                    <input type="radio" name="is_correct" value="${optionCount - 1}" ${optionCount == 1 ? 'checked' : ''}>
                                </div>
                            </div>
                            <input type="text" name="options[]" class="form-control" placeholder="نص الخيار" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#options_container').append(html);
        }

        function removeOption(btn) {
            if ($('.option-row').length > 2) {
                $(btn).closest('.option-row').remove();
            } else {
                alert('يجب ألا يقل عدد الخيارات عن خيارين.');
            }
        }

        $('#question_type').on('change', function () {
            if ($(this).val() === 'multiple_choice') {
                $('#options_wrapper').slideDown();
                if ($('.option-row').length === 0) {
                    addOption(); addOption(); // إضافة خيارين عند البداية
                }
            } else {
                $('#options_wrapper').slideUp();
            }
        });
    </script>
    @endpush
@endsection