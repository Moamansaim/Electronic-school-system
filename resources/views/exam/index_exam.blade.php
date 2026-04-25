@extends('layout-cms.main-layout')
@section('title', 'قائمة الإختبارات')

@section('content')
    <style>
        /* تنسيقات الجدول والقوائم */
        .table-responsive {
            overflow: visible !important;
            min-height: 450px;
        }

        .dropdown-menu {
            z-index: 1060 !important;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
            border-radius: 12px;
        }

        .card {
            border-radius: 15px;
        }

        /* تنسيق رسائل الخطأ والحقول باللون الأحمر الصريح */
        .invalid-feedback {
            display: block !important;
            font-weight: 800 !important;
            color: #dc3545 !important;
            font-size: 0.95rem !important;
            margin-top: 10px;
            padding: 8px 12px;
            background-color: rgba(220, 53, 69, 0.08);
            border-right: 4px solid #dc3545;
            border-radius: 4px;
        }

        .is-invalid {
            border: 2px solid #dc3545 !important;
            background-color: #fff8f8 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        /* تأثير نبض لأيقونة الحذف */
        .pulse-icon {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>

    <div class="container-fluid py-4" dir="rtl">
        <x-grade-level-success-component />
        <x-grade-level-error-component />

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center ">
                    <div class="col-md-6">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-graduation-cap text-primary ml-2"></i> قائمة الإختبارات
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-wrap justify-content-end align-items-center" style="gap: 10px;">
                            <form action="{{ route('exams.index') }}" method="GET" class="ml-2">
                                <div class="input-group border rounded-pill px-2 py-1 bg-light shadow-sm">
                                    <input type="text" name="search" class="form-control bg-transparent border-0 "
                                        placeholder="ابحث عن إختبار..." value="{{ request('search') }}"
                                        style="width: 180px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-link text-muted p-0 px-2" type="submit"><i
                                                class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                            <a href="{{ route('exams.create') }}"
                                class="btn btn-primary shadow-sm px-4 rounded-pill font-weight-bold">
                                <i class="fas fa-plus ml-1"></i> إنشاء إختبار
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">الاختبار</th>
                                <th class="border-0">المدة</th>
                                <th class="border-0">الحالة</th>
                                <th class="border-0">الدرجة الكلية</th>
                                <th class="border-0">المعلم المسؤول</th>
                                <th class="border-0 text-center">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($exams as $exam)
                                @php
                                    $publishedClassIds = $exam->classrooms->pluck('id')->toArray();
                                    $isPublished = count($publishedClassIds) > 0;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="font-weight-bold text-dark">{{ $exam->subject->name }}</span></td>
                                    <td>{{ $exam->duration }} دقيقة</td>
                                    <td>
                                        @if ($isPublished)
                                            <span class="badge badge-pill badge-success px-3 shadow-sm">منشور
                                                ({{ count($publishedClassIds) }})</span>
                                        @else
                                            <span
                                                class="badge badge-pill badge-light text-muted px-3 border shadow-none">غير
                                                منشور</span>
                                        @endif
                                    </td>
                                    <td>{{ $exam->total_marks }}</td>
                                    <td>{{ $exam->teacher->full_name }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-pill shadow-sm px-3" type="button"
                                                id="dropdownMenuButton{{ $exam->id }}" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v text-muted"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right shadow-lg p-2 "
                                                aria-labelledby="dropdownMenuButton{{ $exam->id }}">

                                                <h6 class="dropdown-header text-xs text-muted font-weight-bold">إدارة
                                                    الاختبار</h6>

                                                {{-- زر النشر أو التعديل الذكي --}}
                                                @if ($isPublished)
                                                    <button type="button"
                                                        class="dropdown-item text-info font-weight-bold rounded"
                                                        data-toggle="modal" data-target="#publishModal{{ $exam->id }}">
                                                        <i class="fas fa-edit "></i>
                                                        <span class="ml-2">تعديل النشر</span>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="dropdown-item text-success font-weight-bold rounded"
                                                        data-toggle="modal" data-target="#publishModal{{ $exam->id }}">
                                                        <i class="fas fa-paper-plane "></i>
                                                        <span class="ml-2">نشر الاختبار</span>
                                                    </button>
                                                @endif

                                                {{-- <span class="ml-2" ></span> --}}

                                                @if ($isPublished)
                                                    <form action="{{ route('exams.publish.destroy', $exam->id) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit"
                                                            class="dropdown-item text-danger font-weight-bold rounded"
                                                            onclick=" return confirm('هل أنت متأكد من إيقاف حالة النشر بشكل كلي لجميع الصفوف؟')">
                                                            <i class="fas fa-unlink "></i>
                                                            <span class="ml-2">إيقاف النشر كلياً</span>
                                                        </button>
                                                    </form>
                                                @endif


                                                @if ($isPublished)
                                                    <form action="{{ route('exams.show.classroom', $exam->id) }}"
                                                        method="GET">
                                                        <button type="submit"
                                                            class="dropdown-item text-dark font-weight-bold rounded">
                                                            <i class="fas fa-clipboard-list "></i> <span class="ml-2">
                                                                جدول المتابعة </span>
                                                        </button>
                                                    </form>
                                                @endif



                                                <div class="dropdown-divider"></div>

                                                <a class="dropdown-item rounded"
                                                    href="{{ route('exams.edit', $exam->id) }}">
                                                    <i class="fas fa-pen text-info "></i><span class="ml-2" > تعديل البيانات</span>
                                                </a>
                                                <a class="dropdown-item rounded"
                                                    href="{{ route('exam.questions', $exam->id) }}">
                                                    <i class="fas fa-eye text-primary "></i> <span class="ml-2" > عرض الأسئلة</span>
                                                </a>
                                                <a class="dropdown-item rounded"
                                                    href="{{ route('exams.show', $exam->id) }}">
                                                    <i class="fas fa-plus-circle text-dark "></i><span class="ml-2" > إضافة سؤال</span>
                                                </a>

                                                <div class="dropdown-divider"></div>

                                                <button type="button"
                                                    class="dropdown-item text-danger font-weight-bold rounded"
                                                    data-toggle="modal" data-target="#deleteModal{{ $exam->id }}">
                                                    <i class="fas fa-trash-alt "></i><span class="ml-2" > حذف</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">لا توجد اختبارات متاحة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($exams as $exam)
        @php
            $publishedClassIds = $exam->classrooms->pluck('id')->toArray();
            $isPublished = count($publishedClassIds) > 0;
            $firstPivot = $exam->classrooms->first()?->pivot;
            $startTime = $firstPivot ? date('Y-m-d\TH:i', strtotime($firstPivot->start_time)) : '';
            $endTime = $firstPivot ? date('Y-m-d\TH:i', strtotime($firstPivot->end_time)) : '';

            // منطق الألوان الديناميكي للمودال
            $modalHeaderClass = $isPublished ? 'bg-info text-dark' : 'bg-success text-white';
            $submitBtnClass = $isPublished ? 'btn-info text-dark' : 'btn-success';
            $modalTitle = $isPublished ? 'تعديل بيانات النشر' : 'نشر اختبار جديد';
        @endphp

        {{-- مودال نشر وتعديل الاختبار الذكي --}}
        <div class="modal fade" id="publishModal{{ $exam->id }}" tabindex="-1" role="dialog" aria-hidden="true"
            dir="rtl">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">

                    {{-- هيدر المودال يتغير لونه حسب الحالة --}}
                    <div class="modal-header border-0 {{ $modalHeaderClass }} py-3"
                        style="border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title font-weight-bold mx-auto ">
                            <i class="fas {{ $isPublished ? 'fa-edit' : 'fa-paper-plane' }} ml-2"></i>
                            {{ $modalTitle }}: {{ $exam->subject->name }}
                        </h5>
                    </div>

                    <form action="{{ route('exams.publish.store') }}" method="POST">
                        @csrf
                        <div class="modal-body p-4 ">
                            <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark mb-2">الفصول الدراسية المستهدفة <span
                                        class="text-danger">*</span></label>
                                <select name="classroom_ids[]" multiple
                                    class="form-control border-0 bg-light rounded shadow-none px-3 @if (old('exam_id') == $exam->id) @error('classroom_ids') is-invalid @enderror @endif"
                                    required style="height: 120px;">
                                    @foreach ($exam->teacher->teacherAssignments->unique('classroom_id') as $assignment)
                                        <option value="{{ $assignment->classroom->id }}"
                                            {{ in_array($assignment->classroom->id, old('classroom_ids', $publishedClassIds)) ? 'selected' : '' }}>
                                            {{ $assignment->classroom->name }} - {{ $assignment->gradeLevel->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @if ($isPublished)
                                    <small class="text-danger font-weight-bold d-block mt-2">
                                        <i class="fas fa-exclamation-circle ml-1"></i> تنبيه: تعديل الفصول قد يؤثر على
                                        الطلاب الحاليين.
                                    </small>
                                @else
                                    <small class="text-success font-weight-bold d-block mt-2">يمكنك اختيار أكثر من فصل
                                        باستخدام زر Ctrl.</small>
                                @endif

                                @if (old('exam_id') == $exam->id)
                                    @error('classroom_ids')
                                        <span class="invalid-feedback"><i class="fas fa-exclamation-triangle ml-1"></i>
                                            {{ $message }}</span>
                                    @enderror
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">وقت البدء</label>
                                    <input type="datetime-local" name="start_time"
                                        value="{{ old('exam_id') == $exam->id ? old('start_time') : $startTime }}"
                                        class="form-control border-0 bg-light rounded-pill @if (old('exam_id') == $exam->id) @error('start_time') is-invalid @enderror @endif"
                                        required>
                                    @if (old('exam_id') == $exam->id)
                                        @error('start_time')
                                            <span class="invalid-feedback"><i class="fas fa-exclamation-triangle ml-1"></i>
                                                {{ $message }}</span>
                                        @enderror
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">وقت الانتهاء</label>
                                    <input type="datetime-local" name="end_time"
                                        value="{{ old('exam_id') == $exam->id ? old('end_time') : $endTime }}"
                                        class="form-control border-0 bg-light rounded-pill @if (old('exam_id') == $exam->id) @error('end_time') is-invalid @enderror @endif"
                                        required>
                                    @if (old('exam_id') == $exam->id)
                                        @error('end_time')
                                            <span class="invalid-feedback"><i class="fas fa-exclamation-triangle ml-1"></i>
                                                {{ $message }}</span>
                                        @enderror
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 justify-content-center pb-4">
                            <button type="button" class="btn btn-light px-4 rounded-pill font-weight-bold shadow-sm"
                                data-dismiss="modal">تراجع</button>
                            {{-- زر الإرسال يتغير لونه حسب الحالة --}}
                            <button type="submit"
                                class="btn {{ $submitBtnClass }} px-5 rounded-pill font-weight-bold shadow">
                                {{ $isPublished ? 'تحديث النشر' : 'نشر الاختبار الآن' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- مودال الحذف --}}
        <div class="modal fade" id="deleteModal{{ $exam->id }}" tabindex="-1" role="dialog" aria-hidden="true"
            dir="rtl">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-body p-5 text-center">
                        <div class="text-danger mb-4"><i class="fas fa-exclamation-triangle fa-3x pulse-icon"></i></div>
                        <h4 class="font-weight-bold">حذف الاختبار؟</h4>
                        <p class="text-muted small">هل أنت متأكد من حذف اختبار
                            <strong>{{ $exam->subject->name }}</strong>؟ لا يمكن التراجع عن هذا الإجراء.</p>
                        <div class="d-flex justify-content-center mt-4" style="gap: 15px;">
                            <button type="button" class="btn btn-light px-4 rounded-pill font-weight-bold shadow-sm"
                                data-dismiss="modal">إلغاء</button>
                            <form action="{{ route('exams.destroy', $exam->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="btn btn-danger px-4 rounded-pill font-weight-bold shadow">نعم، احذف</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // كود لفتح المودال تلقائياً في حال وجود أخطاء فلديشن راجعة من السيرفر
            @if ($errors->any())
                var failedExamId = "{{ old('exam_id') }}";
                if (failedExamId) {
                    $('#publishModal' + failedExamId).modal('show');
                }
            @endif
        });
    </script>
@endpush
