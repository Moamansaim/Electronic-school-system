@extends('layout-cms.main-layout')
@section('title', 'الملخصات التعليمية ')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-secondary border-bottom pb-2">📚 المواد الدراسية المسجلة</h3>
            <p class="text-muted">اختر المادة لاستعراض الملخصات والملفات التعليمية.</p>
        </div>
    </div>

    <div class="row">
      @forelse ($subjects_student->subjects as $subject_student )
           <div class="col-md-4 col-sm-6 mb-4">
            <a href="{{ route('student.files.summary.list' , $subject_student->id ) }}" class="text-decoration-none">
                <div class="card h-100 subject-card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3 mx-auto shadow-sm">
                            <i class="fas fa-book-open fa-2x text-white"></i>
                        </div>
                        
                        <h5 class="card-title fw-bold text-dark mb-2"></h5>
                        <p class="card-text text-muted text-blod" style="font-size: 20px">
                              {{ $subject_student->name ?? 'غير محدد' }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 bg-light p-2 rounded">
                            <span class="badge bg-primary rounded-pill">{{ is_array($subject_student) && isset($subject_student['files']) ?  count($subject_student['files']) : (optional($subject_student->files)->count() ?? 0) }} ملخص</span>
                            <span class="text-primary small fw-bold">عرض المحتوى <i class="fas fa-chevron-left ms-1"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
      @empty
          
      @endforelse
       
      
    </div>
</div>

<style>
    .subject-card {
        transition: all 0.3s ease;
        border-radius: 15px;
    }
    .subject-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-box {
        width: 70px;
        height: 70px;
        background: linear-gradient(45deg, #4e73df, #224abe);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card-title {
        color: #2e3a59;
    }
    /* تنسيق يتناسب مع ألوان لوحة التحكم في الصورة */
    .bg-primary {
        background-color: #007bff !important;
    }
</style>
@endsection
