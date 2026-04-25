@extends('layout-cms.main-layout')
@section('title', 'تعديل السؤال')

@section('content')
    <div class="p-4">
        <x-grade-level-error-component />
        <x-grade-level-success-component />
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary font-weight-bold">تعديل السؤال</h5>
            </div>

            <form action="{{ route('questions.update', $question->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                <div class="card-body p-5">
                    {{-- نص السؤال --}}
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">نص السؤال</label>
                        <input type="text" name="question_text"
                            class="form-control @error('question_text') is-invalid @enderror"
                            value="{{ old('question_text', $question->question_text) }}">

                        @error('question_text')
                          <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- نوع السؤال --}}
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">نوع السؤال</label>
                        <select name="question_type" id="question_type"
                            class="form-control @error('question_type') is-invalid @enderror">
                            <option value=" ">اختر نوع السؤال</option> 
                            <option value="multiple_choice"
                                {{ old('question_type', $question->question_type) == 'multiple_choice' ? 'selected' : '' }}>
                                اختيار من متعدد</option>
                        </select>

                        @error('question_type')
                          <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- درجة السؤال --}}
                    <div class="form-group mb-4">
                        <label for="mark" class="font-weight-bold">درجة السؤال</label>
                        <input type="number" name="mark" value="{{ old('mark', $question->mark) }}"
                            class="form-control @error('mark') is-invalid @enderror">

                        @error('mark')
                      <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- قسم الخيارات الديناميكي --}}
                    <div id="options_wrapper"
                        style="display: {{ old('question_type', $question->question_type) == 'multiple_choice' ? 'block' : 'none' }};">
                        <div class="card border-primary mb-4" style="border-radius: 10px; border-style: dashed;">
                            <div class="card-body bg-light">
                                <h6 class="font-weight-bold mb-3 text-primary">خيارات الإجابة</h6>

                                {{-- إظهار خطأ إذا لم يتم اختيار إجابة صحيحة أو إذا كانت الخيارات ناقصة --}}
                                @error('is_correct')
                                    <div class="alert alert-danger py-2 px-3 mb-3 small">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $message }}
                                    </div>
                                @enderror
                                @error('options')
                                    <div class="alert alert-danger py-2 px-3 mb-3 small">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div id="options_container">
                                    @foreach ($question->options as $index => $option)
                                        <div class="row mb-2 option-row align-items-center">
                                            <div class="col-md-10">
                                                <div class="input-group shadow-sm">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-white">
                                                            <input type="radio" name="is_correct"
                                                                value="{{ $index }}"
                                                                {{ old('is_correct', $option->is_correct) ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="option_ids[]" value="{{ $option->id }}">
                                                    <input type="text" name="options[]"
                                                        class="form-control @error("options.$index") is-invalid @enderror"
                                                        value="{{ old("options.$index", $option->option_text) }}" required>
                                                </div>
                                                {{-- خطأ لكل خيار على حدة --}}
                                                @error("options.$index")
                                                    <span class="text-danger small"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeOption(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-3" onclick="addOption()">
                                    <i class="fas fa-plus"></i> إضافة خيار جديد
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 text-left">
                    <a href="{{ route('questions.index', $question->exam_id) }}" class="btn btn-light px-4">العودة للقائمة السابقة</a>
                    <button type="submit" class="btn btn-success px-5 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-sync-alt ml-2"></i> تحديث البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('script')
        <script>
            let optionCount = {{ $question->options->count() }};

            function addOption() {
                optionCount++;
                let html = `
                    <div class="row mb-2 option-row align-items-center">
                        <div class="col-md-10">
                            <div class="input-group shadow-sm">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-white">
                                        <input type="radio" name="is_correct" value="new_${optionCount}">
                                    </div>
                                </div>
                                <input type="hidden" name="option_ids[]" value="new_${optionCount}">
                                <input type="text" name="options[]" class="form-control" placeholder="نص الخيار الجديد" required>
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

            $('#question_type').on('change', function() {
                if ($(this).val() === 'multiple_choice') {
                    $('#options_wrapper').slideDown();
                } else {
                    $('#options_wrapper').slideUp();
                }
            });
        </script>
    @endpush
@endsection
