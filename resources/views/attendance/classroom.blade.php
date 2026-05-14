@extends('layout-cms.main-layout')
@section('title', 'صفحة الصفوف الطلابية الخاصة بالحضور والطلاب')

@section('content')

    <div class="container mt-4" dir="rtl">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-right">نظام الحضور والغياب - اختر الصف الدراسي</h2>
                <p class="text-muted">يرجى اختيار الصف لبدء عملية تسجيل الحضور والغياب لليوم.</p>
            </div>
        </div>

        <div class="row">
            @forelse($classrooms as $classroom)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 transition-hover">
                        <div class="card-body text-center">
                            <!-- أيقونة تعبيرية للفصل -->
                            <div class="icon-box mb-3">
                                <i class="fa fa-users fa-3x text-primary"></i>
                            </div>

                            <h4 class="card-title font-weight-bold">{{ $classroom->name }}</h4>
                            <p class="card-text text-muted">
                                عدد الطلاب: {{ $classroom->students->count() }} طالب
                            </p>

                            <hr>

                            <!-- رابط جلب طلاب هذا الصف الدراسي -->
                            <a href="{{ route('attendance.create', $classroom->id) }}" class="btn btn-primary btn-block">
                                <i class="fa fa-clipboard-check ml-1"></i>
                                بدء تسجيل الحضور
                            </a>
                        </div>
                        
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        لا يوجد صفوف دراسية مضافة حالياً.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        /* لمسة جمالية للبطاقات */
        .transition-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease-in-out;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .card-title {
            color: #2c3e50;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }
    </style>
@endsection