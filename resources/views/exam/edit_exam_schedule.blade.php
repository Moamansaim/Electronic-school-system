@extends('layout-cms.main-layout')
@section('title', 'تعديل موعد اختبار')

@section('content')
    <div class="p-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-success font-weight-bold">
                    <i class="fas fa-edit mr-2"></i> تعديل موعد: {{ $dataExamSchedule->subject->name }}
                </h5>
            </div>

            <form action="{{ route('data_exam_schedules.update', $dataExamSchedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-5">
                    <div class="row">
                           <div class="col-md-12 mb-4">
                            <input type="hidden" name="exam_schedule_id" value="{{ $dataExamSchedule->exam_schedule_id  ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold mb-2">المادة الدراسية</label>
                            <select name="subject_id" class="form-control">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $dataExamSchedule->subject_id == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                 
                        <div class="col-md-4 mb-4">
                            <label class="font-weight-bold mb-2">التاريخ</label>
                            <input type="date" name="exam_date" id="exam_date" value="{{ $dataExamSchedule->exam_date }}"
                                class="form-control">
                                @error('exam_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="font-weight-bold mb-2">اليوم</label>
                            <input type="text" name="exam_day" id="exam_day" value="{{ $dataExamSchedule->exam_day }}"
                                class="form-control" readonly>
                                @error('exam_day') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-2 mb-4">
                            <label class="font-weight-bold mb-2">البدء</label>
                            <input type="time" name="start_time" value="{{ $dataExamSchedule->start_time }}"
                                class="form-control">
                                @error('start_time') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-2 mb-4">
                            <label class="font-weight-bold mb-2">الانتهاء</label>
                            <input type="time" name="end_time" value="{{ $dataExamSchedule->end_time }}"
                                class="form-control">
                                @error('end_time') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-3 d-flex justify-content-between">
                    <a href="{{ route('exam-schedules.index') }}" class="btn btn-light">إلغاء</a>
                    <button type="submit" class="btn btn-success px-5">تحديث البيانات</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('exam_date').addEventListener('change', function () {
            const date = new Date(this.value);
            const options = { weekday: 'long' };
            document.getElementById('exam_day').value = new Intl.DateTimeFormat('ar-EG', options).format(date);
        });
    </script>
@endsection