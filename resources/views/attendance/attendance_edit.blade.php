@extends('layout-cms.main-layout')
@section('title', 'صفحة تعديل سجل الحضور  والغياب الخاص  بالطالب')

@section('content')

<div id="alert-container" style="position: fixed; top: 20px; left: 20px; z-index: 9999; width: 300px;"></div>

<table class="table">
    <thead>
        <tr>
            <th>اسم الطالب</th>
            <th>الحالة (تحديث تلقائي)</th>
            <th>العمليات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $student->full_name }}</td>
                <td>
                    <select class="form-select status-select" data-id="{{ $log->id }}">
                        <option value="present" {{ $log->status == 'present' ? 'selected' : '' }}>حاضر</option>
                        <option value="absent" {{ $log->status == 'absent' ? 'selected' : '' }}>غائب</option>
                        <option value="excused" {{ $log->status == 'excused' ? 'selected' : '' }}>غائب بعذر</option>
                        <option value="late" {{ $log->status == 'late' ? 'selected' : '' }}>متأخر</option>
                    </select>
                </td>
                <td>
                    <form action="{{ route('attendance.delete', $log->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


<script>


    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function () {
            const attendanceId = this.getAttribute('data-id');
            const newStatus = this.value;
            const alertContainer = document.getElementById('alert-container');

            // إرسال الطلب باستخدام Fetch API
            fetch(`/attendance/update/${attendanceId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // ضروري جداً في لارافيل
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // إظهار رسالة نجاح عائمة
                        showAlert(data.message, 'success');
                    }
                })
                .catch(error => {
                    showAlert('حدث خطأ أثناء التحديث', 'danger');
                    console.error('Error:', error);
                });
        });
    });

    // دالة بسيطة لإنشاء التنبيه وإخفائه تلقائياً
    function showAlert(message, type) {
        const container = document.getElementById('alert-container');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} shadow-lg`;
        alert.innerText = message;

        container.appendChild(alert);

        // إخفاء الرسالة بعد 3 ثوانٍ
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    }

</script>
@endsection