@extends('layout-cms.main-layout')
@section('title', 'إضافة حصة دراسية')

@section('content')
    <div class="p-4">
        <x-grade-level-error-component />
        <x-grade-level-success-component />
        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary font-weight-bold">
                    <i class="fas fa-plus-circle mr-2"></i> إضافة حصة دراسية
                </h5>
            </div>

            <form action="{{ route('class-schedules.store') }}" method="POST">
                @csrf
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label font-weight-bold">اسم المعلم</label>
                            <input type="text" class="form-control bg-light" readonly value="{{ $teacher->full_name }}">
                            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label class="form-label font-weight-bold mb-2">اليوم</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i
                                        class="fas fa-layer-group text-muted"></i></span>
                            </div>
                            <select name="day" class="form-control border-left-0 @error('day') is-invalid @enderror">
                                @foreach ($week_days as $week_day)
                                    <option value="{{ $week_day->value }}">{{ $week_day->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('day')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label font-weight-bold mb-2">الحصة الدراسية</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i
                                        class="fas fa-layer-group text-muted"></i></span>
                            </div>
                            <select name="class_schedule"
                                class="form-control border-left-0 @error('class_schedule') is-invalid @enderror">
                                @foreach ($class_schedules as $class_schedule)
                                    <option value="{{ $class_schedule->value }}">{{ $class_schedule->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('class_schedule')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label font-weight-bold mb-2">الصف الدراسي </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i
                                        class="fas fa-layer-group text-muted"></i></span>
                            </div>
                            <select name="classroom_id"
                                class="form-control border-left-0 @error('classroom_id') is-invalid @enderror">
                                <option value="">اختر الصف</option>
                                @foreach ($teacher->teacherAssignments as $assignment)
                                    <option value="{{ $assignment->classroom_id }}">{{ $assignment->classroom->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('classroom_id')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
        </div>

        <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('classrooms.index') }}" class="text-muted">
                <i class="fas fa-arrow-right mr-1"></i> العودة للقائمة
            </a>
            <button type="submit" class="btn btn-primary px-5 shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-save ml-2"></i> حفظ
            </button>
        </div>
        </form>
    </div>
    </div>
@endsection