@extends('layout-cms.main-layout')
@section('title', 'تعديل  مادة دراسية')

@section('content')
<div class="p-4">
    <x-grade-level-error-component />

    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-success font-weight-bold">
                <i class="fas fa-edit mr-2"></i> تعديل بيانات: {{ $subject->name }}
            </h5>
        </div>

        <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card-body p-5">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label font-weight-bold mb-2">اسم المادة الدراسية </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-chalkboard text-muted"></i>
                                </span>
                            </div>
                            <input type="text" name="name" 
                                   value="{{ old('name', $subject->name) }}" 
                                   class="form-control border-left-0 @error('name') is-invalid @enderror" 
                                   placeholder="مثال:  مادة اللغة العربية - الثانوية">
                        </div>
                        @error('name')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label font-weight-bold mb-2">المرحلة الدراسية</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-layer-group text-muted"></i>
                                </span>
                            </div>
                            <select name="grade_level_id" class="form-control border-left-0 @error('grade_level_id') is-invalid @enderror">
                                @foreach ($grade_levels as $grade_level)
                                    <option value="{{ $grade_level->id }}" 
                                        {{ old('grade_level_id', $subject->grade_level_id) == $grade_level->id ? 'selected' : '' }}>
                                        {{ $grade_level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('grade_level_id')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('classrooms.index') }}" class="text-muted">
                    <i class="fas fa-arrow-right mr-1"></i> العودة للقائمة 
                </a>
                <button type="submit" class="btn btn-success px-5 shadow-sm" style="border-radius: 8px;">
                  <i class="fas fa-sync-alt ml-2"></i>  تحديث البيانات 
                </button>
            </div>
        </form>
    </div>
</div>
@endsection