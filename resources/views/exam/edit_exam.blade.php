@extends('layout-cms.main-layout')
@section('title', 'تعديل إختبار')

@section('content')
    <div class="p-4">
        <x-grade-level-error-component />

        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
             <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-success font-weight-bold">
                    <i class="fas fa-edit mr-2"></i> تعديل بيانات إختبار : {{ $exam->subject->name }}
                </h5>
            </div>

            <form action="{{ route('exams.update' , $exam->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-5">
                     <div class="row">
                          @auth
                              <div class="col-md-12 mb-4">
                                <input type="hidden" name="teacher_id" value="{{ auth()->user()->teacher->id }}">
                              </div>
                          @endauth
                          
                          <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">اختر المادة الدراسية</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-book text-muted"></i></span>
                                </div>
                                <select name="subject_id" class="form-control border-left-0 @error('subject_id') is-invalid @enderror">
                                    @foreach ($teacher_subjects as $teacher_subject)
                                        <option value="{{ $teacher_subject->subject->id }}" {{ old('subject_id' , $exam->subject_id ) == $teacher_subject->subject->id ? 'selected' : '' }}>
                                            {{ $teacher_subject->subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('subject_id') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">مدة الإختبار بالدقائق</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-clock text-muted"></i></span>
                                </div>
                                <input type="number" name="duration" value="{{ old('duration', $exam->duration) }}" class="form-control border-left-0 @error('duration') is-invalid @enderror">
                            </div>
                            @error('duration') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                        </div>
                     </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">درجة الإختبار الكلية</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-check-double text-muted"></i></span>
                                </div>
                                <input type="number" name="total_marks" value="{{ old('total_marks', $exam->total_marks) }}" class="form-control border-left-0 @error('total_marks') is-invalid @enderror">
                            </div>
                            @error('total_marks') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">نوع الإختبار</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-list-ul text-muted"></i></span>
                                </div>
                                <select id="exam_type" name="exam_type" class="form-control border-left-0 @error('exam_type') is-invalid @enderror">
                                    @foreach ($exam_types as $exam_type)
                                        <option value="{{ $exam_type->value }}" {{ old('exam_type', $exam->exam_type) == $exam_type->value ? 'selected' : '' }}>
                                            {{ $exam_type->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('exam_type') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    
                    <div class="row" id="month_wrapper"></div>
                </div>

                <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('exams.index') }}" class="text-muted"><i class="fas fa-arrow-right mr-1"></i> العودة للقائمة</a>
                    <button type="submit" class="btn btn-success px-5 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-sync-alt ml-2"></i> تحديث البيانات 
                    </button>
                </div>
            </form>
        </div>
    </div>

@push('script')
<script>
    $(document).ready(function() {
        var wrapper = $('#month_wrapper');
        var oldMonth = "{{ old('month', $exam->month) }}"; // استرجاع الشهر من قاعدة البيانات أو المدخلات السابقة

        $('#exam_type').on('change', function() {
            var selected = $(this).val().trim();

            if (selected === "شهري") {
                var monthHtml = `
                    <div class="col-md-6 mb-4" id="dynamic_month_field">
                        <label class="form-label font-weight-bold mb-2">اختر الشهر</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            </div>
                            <select name="month" class="form-control border-left-0" required>
                                <option value="">-- اختر الشهر --</option>
                                <option value="يناير" ${oldMonth == 'يناير' ? 'selected' : ''}>يناير</option>
                                <option value="فبراير" ${oldMonth == 'فبراير' ? 'selected' : ''}>فبراير</option>
                                <option value="مارس" ${oldMonth == 'مارس' ? 'selected' : ''}>مارس</option>
                                <option value="أبريل" ${oldMonth == 'أبريل' ? 'selected' : ''}>أبريل</option>
                                <option value="مايو" ${oldMonth == 'مايو' ? 'selected' : ''}>مايو</option>
                                <option value="يونيو" ${oldMonth == 'يونيو' ? 'selected' : ''}>يونيو</option>
                                <option value="يوليو" ${oldMonth == 'يوليو' ? 'selected' : ''}>يوليو</option>
                                <option value="أغسطس" ${oldMonth == 'أغسطس' ? 'selected' : ''}>أغسطس</option>
                                <option value="سبتمبر" ${oldMonth == 'سبتمبر' ? 'selected' : ''}>سبتمبر</option>
                                <option value="أكتوبر" ${oldMonth == 'أكتوبر' ? 'selected' : ''}>أكتوبر</option>
                                <option value="نوفمبر" ${oldMonth == 'نوفمبر' ? 'selected' : ''}>نوفمبر</option>
                                <option value="ديسمبر" ${oldMonth == 'ديسمبر' ? 'selected' : ''}>ديسمبر</option>
                            </select>
                        </div>
                    </div>`;
                wrapper.html(monthHtml);
            } else {
                wrapper.empty();
            }
        });

        // تشغيل الحدث عند فتح الصفحة للتأكد من ظهور الشهر إذا كان الاختبار شهرياً
        $('#exam_type').trigger('change');
    });
</script>
@endpush
@endsection