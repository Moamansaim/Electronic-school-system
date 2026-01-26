@extends('layout-cms.main-layout')
@section('title', 'تعيين مواد للمعلم')
@section('content')
    <div class="p-4">
        <x-grade-level-error-component />

        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary font-weight-bold">
                    <i class="fas fa-link mr-2"></i> تعيين المواد والصفوف للمعلم
                </h5>
            </div>

            <form action="{{ route('teachers.storeAssignment') }}" method="POST">
                @csrf
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label font-weight-bold">اسم المعلم</label>
                            <input type="text" class="form-control bg-light" readonly
                                value="{{ $teacher->first_name . ' ' . $teacher->father_name . ' ' . $teacher->family_name }}">
                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-muted mb-4"><i class="fas fa-graduation-cap mr-2"></i> بيانات التعيين الدراسي</h6>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold">المرحلة الدراسية</label>
                            <select name="grade_level_id" id="grade_level"
                                class="form-control select-search @error('grade_level_id') is-invalid @enderror" required>
                                <option value="">اختر المرحلة...</option>
                                @foreach ($grade_levels as $grade_level)
                                    <option value="{{ $grade_level->id }}">{{ $grade_level->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold">الصف الدراسي</label>
                            <select name="classroom_id" id="classroom"
                                class="form-control select-search @error('classroom_id') is-invalid @enderror" required>
                                <option value="">اختر المرحلة أولاً...</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold">المواد المسندة</label>
                            {{-- تم إضافة [] و multiple ليدعم اختيار أكثر من مادة --}}
                            <select name="subject_id" id="subject" class="form-control select-search"
                                data-placeholder="اختر مادة  ">
                                <option value="">اختر المرحلة أولاً...</option>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-3 d-flex justify-content-between">
                    <a href="{{ route('teachers.index') }}" class="btn btn-link text-muted">العودة للقائمة</a>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                        <i class="fas fa-save ml-2"></i> حفظ التعيين
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                $('#grade_level').on('change', function() {
                    var gradeId = $(this).val();
                    var classroomSelect = $('#classroom');
                    var subjectSelect = $('#subject');

                    if (gradeId) {
                        classroomSelect.html('<option value="">جاري التحميل...</option>');
                        subjectSelect.html('<option value="">جاري التحميل...</option>');

                        // 1. نجهز الرابط باستخدام الـ Route Name ونضع علامة مؤقتة :id
                        var url = "{{ route('teachers.get-data-by-grade', ':id') }}";
                        // 2. نستبدل العلامة المؤقتة بمتغير الـ JS الحقيقي
                        url = url.replace(':id', gradeId);

                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                classroomSelect.empty().append(
                                    '<option value="">اختر الصف...</option>');
                                $.each(data.classrooms, function(key, value) {
                                    classroomSelect.append('<option value="' + value.id +
                                        '">' + value.name + '</option>');
                                });
                                subjectSelect.empty().append(
                                    '<option value="">اختر المادة...</option>');
                                $.each(data.subjects, function(key, value) {
                                    subjectSelect.append('<option value="' + value.id +
                                        '">' + value.name + '</option>');
                                });
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText); // لتتبع الخطأ في الـ Console
                                alert('حدث خطأ أثناء جلب البيانات، يرجى المحاولة لاحقاً.');
                            }
                        });
                    } else {
                        classroomSelect.empty().append('<option value="">اختر المرحلة أولاً...</option>');
                        subjectSelect.empty().append('<option value="">اختر المرحلة أولاً...</option>');
                    }
                });
            });
        </script>
    @endpush

@endsection
