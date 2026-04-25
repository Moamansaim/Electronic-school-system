@extends('layout-cms.main-layout')
@section('title', ' تعديل طالب ')
@section('content')
    <div class="p-4">
        <x-grade-level-error-component />

        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-success font-weight-bold">
                    <i class="fas fa-edit mr-2"></i> تعديل بيانات الطالب:
                    {{ $student->first_name . ' ' . $student->father_name . ' ' . $student->family_name }}
                </h5>
            </div>

            <form action="{{ route('students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-5">
                    <div class="row ">
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">اسم الطالب </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="first_name"
                                    value="{{ old('first_name', $student->first_name) }}"
                                    class="form-control border-left-0 @error('first_name') is-invalid @enderror"
                                    placeholder="أدخل الاسم الأول">
                            </div>
                            @error('first_name')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                     
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">اسم الأب </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-user-friends text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="father_name"
                                    value="{{ old('father_name', $student->father_name) }}"
                                    class="form-control border-left-0 @error('father_name') is-invalid @enderror"
                                    placeholder="أدخل اسم الأب">
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">اسم الجد </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-users text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="grandfather_name"
                                    value="{{ old('grandfather_name', $student->grandfather_name) }}"
                                    class="form-control border-left-0 @error('grandfather_name') is-invalid @enderror"
                                    placeholder="أدخل اسم الجد">
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">اسم العائلة </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-id-card-alt text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="family_name"
                                    value="{{ old('family_name', $student->family_name) }}"
                                    class="form-control border-left-0 @error('family_name') is-invalid @enderror"
                                    placeholder="أدخل اسم العائلة">
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">تاريخ الميلاد </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                    </span>
                                </div>
                                <input type="date" name="date_of_birth"
                                    value="{{ old('date_of_birth', $student->date_of_birth) }}"
                                    class="form-control border-left-0 @error('date_of_birth') is-invalid @enderror">
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">رقم الهوية </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-fingerprint text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="national_id"
                                    value="{{ old('national_id', $student->national_id) }}"
                                    class="form-control border-left-0 @error('national_id') is-invalid @enderror"
                                    placeholder="أدخل رقم الهوية">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row ">
                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold mb-2">المدينة </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i
                                            class="fas fa-city text-muted"></i></span>
                                </div>
                                <input type="text" name="city" value="{{ old('city', $student->city) }}"
                                    class="form-control border-left-0" placeholder="المدينة">
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold mb-2">الحي </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i
                                            class="fas fa-map-marker-alt text-muted"></i></span>
                                </div>
                                <input type="text" name="district" value="{{ old('district', $student->district) }}"
                                    class="form-control border-left-0" placeholder="الحي">
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold mb-2">الشارع</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i
                                            class="fas fa-road text-muted"></i></span>
                                </div>
                                <input type="text" name="street" value="{{ old('street', $student->street) }}"
                                    class="form-control border-left-0" placeholder="الشارع">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row ">
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">أرقام الجوال</label>
                            <div id="phone-container">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-success btn-sm add-phone">
                                        <i class="fas fa-plus ml-1"></i> إضافة رقم جديد
                                    </button>
                                </div>
                                @foreach ($student->phones as $phone)
                                    <div class="input-group mb-2 phone-item">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0"><i
                                                    class="fas fa-mobile-alt text-muted"></i></span>
                                        </div>
                                        <input type="text" value="{{ $phone->phone_number }}"
                                            name="phone_numbers[]" class="form-control border-left-0"
                                            placeholder="أدخل رقم الجوال">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger remove-phone">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">المرحلة الدراسية</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i
                                            class="fas fa-layer-group text-muted"></i></span>
                                </div>
                                <select name="grade_level_id" id="grade_level"
                                    class="form-control border-left-0 @error('grade_level_id') is-invalid @enderror">
                                    @foreach ($grade_levels as $grade_level)
                                        <option value="{{ $grade_level->id }}"
                                            {{ old('grade_level_id', $student->grade_level_id) == $grade_level->id ? 'selected' : '' }}>
                                            {{ $grade_level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row text-right" dir="rtl">
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">الصف الدراسي</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0"><i
                                            class="fas fa-chalkboard text-muted"></i></span>
                                </div>
                                <select name="classroom_id" id="classroom" class="form-control border-left-0" required>
                                    <option value="">اختر الصف...</option>
                                    @if ($student->gradeLevel)
                                        @foreach ($student->gradeLevel->classrooms as $classroom)
                                            <option value="{{ $classroom->id }}"
                                                {{ old('classroom_id', $student->classroom_id) == $classroom->id ? 'selected' : '' }}>
                                                {{ $classroom->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">المواد المسندة</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-book text-muted"></i>
                                    </span>
                                </div>
                                <select name="subject_ids[]" id="subject"
                                    class="form-control border-left-0 select-search" multiple>
                                    @php
                                        // 1. تحديد المرحلة الدراسية المختارة (إما من الخطأ السابق أو من بيانات الطالب الأصلية)
                                        $selectedGradeId = old('grade_level_id', $student->grade_level_id);
                                        $selectedGrade = $grade_levels->find($selectedGradeId);

                                        // 2. تحديد المواد المختارة (إما من الخطأ السابق أو من مواد الطالب المسجلة فعلياً)
                                        $currentSubjects = old(
                                            'subject_ids',
                                            $student->subjects->pluck('id')->toArray(),
                                        );
                                    @endphp

                                    @if ($selectedGrade)
                                        @foreach ($selectedGrade->subjects as $subject)
                                            <option value="{{ $subject->id }}"
                                                {{ in_array($subject->id, $currentSubjects) ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">اختر المرحلة أولاً...</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('students.index') }}" class="text-muted">
                        <i class="fas fa-arrow-right mr-1"></i> العودة للقائمة السابقة
                    </a>
                    <button type="submit" class="btn btn-success px-5 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-sync-alt ml-2"></i> تحديث البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('phone-container');
            container.addEventListener('click', function(e) {
                if (e.target.closest('.add-phone')) {
                    const newItem = document.createElement('div');
                    newItem.className = 'input-group mb-2 phone-item';
                    newItem.innerHTML = `
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-mobile-alt text-muted"></i></span>
                        </div>
                        <input type="text" name="phone_numbers[]" class="form-control border-left-0" placeholder="أدخل رقم الجوال">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-phone"><i class="fas fa-trash-alt"></i></button>
                        </div>`;
                    container.appendChild(newItem);
                }
                if (e.target.closest('.remove-phone')) {
                    e.target.closest('.phone-item').remove();
                }
            });
        });

        $(document).ready(function() {
            $('#grade_level').on('change', function() {
                var gradeId = $(this).val();
                var classroomSelect = $('#classroom');
                var subjectSelect = $('#subject');

                if (gradeId) {
                    classroomSelect.html('<option value="">جاري التحميل...</option>');
                    subjectSelect.html('<option value="">جاري التحميل...</option>');

                    var url = "{{ route('students.get-data-by-grade', ':id') }}";
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

                            subjectSelect.empty();
                            $.each(data.subjects, function(key, value) {
                                subjectSelect.append('<option selected value="' + value
                                    .id +
                                    '">' + value.name + '</option>');
                            });

                            subjectSelect.css({
                                "pointer-events": "none",
                                "background-color": "#e9ecef"
                            }).attr("tabindex", "-1");
                        }

                    });
                } else {
                    classroomSelect.empty().append('<option value="">اختر المرحلة أولاً...</option>');
                    subjectSelect.empty().append('<option value="">اختر المرحلة أولاً...</option>');
                    subjectSelect.css({
                        "pointer-events": "auto",
                        "background-color": "#fff"
                    }).removeAttr("tabindex");
                }
            });
        });
    </script>
@endpush
