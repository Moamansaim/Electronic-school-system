@extends('layout-cms.main-layout')
@section('title', 'الملف الأكاديمي | ' . $student->first_name)

@section('content')
    <div class="container-fluid p-4" dir="rtl">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
            <div>
                <h4 class="font-weight-bold text-secondary mb-0">بطاقة الطالب الأكاديمية</h4>

            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('students.index') }}" class="btn btn-secondary  shadow-sm px-4 rounded-pill mr-2">
                    <i class="fas fa-chevron-right ml-1"></i> العودة للقائمة
                </a>
                <button id="printProfile" onclick="window.print();" class="btn btn-primary shadow-sm px-4 rounded-pill ">
                    <i class="fas fa-print ml-1"></i> طباعة الملف
                </button>

            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 20px;">
                    <div class="card-header border-0 pb-0 pt-4 bg-white text-center">
                        <div class="avatar-container position-relative">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm"
                                style="background: linear-gradient(135deg, #007bff, #6610f2); color: #fff; width: 110px; height: 110px; font-size: 3rem;">
                                {{ mb_substr($student->first_name, 0, 1) }}
                            </div>
                            <span class="status-indicator bg-success"></span>
                        </div>
                        <h5 class="font-weight-bold mt-3 mb-1 text-dark">{{ $student->first_name }}
                            {{ $student->family_name }}</h5>
                        <p  id="school_id">رقم الطالب المدرسي : {{ $student->user->school_id }}</p>
                    </div>

                    <div class="card-body pt-0">
                        <hr class="my-3 opacity-5">
                        <div class="info-list">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box-sm bg-soft-info ml-3"><i class="fas fa-fingerprint text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">رقم الهوية الوطنية</small>
                                    <span class="font-weight-bold">{{ $student->national_id }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box-sm bg-soft-warning ml-3"><i
                                        class="fas fa-birthday-cake text-warning"></i></div>
                                <div>
                                    <small class="text-muted d-block">تاريخ الميلاد</small>
                                    <span class="font-weight-bold">{{ $student->date_of_birth }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 rounded bg-light border-0">
                            <h6 class="small font-weight-bold text-primary mb-2"><i class="fas fa-shield-alt ml-1"></i>
                                الحالة الأكاديمية</h6>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small text-muted">المرحلة:</span>
                                <span class="small font-weight-bold">{{ $student->gradeLevel->name ?? 'غير محدد' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small text-muted">الصف:</span>
                                <span
                                    class="small font-weight-bold text-success">{{ $student->classroom->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <ul class="nav nav-pills mb-4 bg-light p-1 rounded-pill" id="studentTab" role="tablist"
                            style="width: fit-content;">
                            <li class="nav-item">
                                <a class="nav-link active rounded-pill px-4" id="profile-tab" data-toggle="tab"
                                    href="#profile">البيانات الشخصية</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill px-4" id="subjects-tab" data-toggle="tab"
                                    href="#subjects">الجدول الدراسي</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="studentTabContent">
                            <div class="tab-pane fade show active" id="profile">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h6 class="section-title">الاسم الرباعي بالكامل</h6>
                                        <p class="h6 bg-light p-3 rounded border-right-bold">{{ $student->first_name }}
                                            {{ $student->father_name }} {{ $student->grandfather_name }}
                                            {{ $student->family_name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <h6 class="section-title">العنوان الجغرافي</h6>
                                        <p class="h6 bg-light p-3 rounded border-right-bold">{{ $student->city }}،
                                            {{ $student->district }}</p>
                                    </div>
                                </div>

                                <h6 class="section-title mt-2 mb-3"><i class="fas fa-headset ml-2"></i> قنوات التواصل
                                    والطوارئ</h6>
                                <div class="row">
                                    @forelse ($student->phoneNumbers as $phone)
                                        <div class="col-md-4 mb-3">
                                            <div class="contact-card p-3 border rounded d-flex align-items-center">
                                                <div class="icon-circle bg-success-light ml-3">
                                                    <i class="fab fa-whatsapp text-success"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">رقم التواصل</small>
                                                    <span class="font-weight-bold">{{ $phone->phone_number }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted italic">لا توجد بيانات اتصال مسجلة.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="tab-pane fade" id="subjects">
                                <h6 class="section-title mb-4">المقررات الدراسية المعتمدة لهذا الفصل</h6>
                                <div class="row">
                                    @forelse ($student->subjects as $subject)
                                        <div class="col-md-6 col-xl-4 mb-3">
                                            <div class="subject-card p-3 shadow-sm border-0 d-flex align-items-center">
                                                <div class="subject-icon shadow-sm">{{ mb_substr($subject->name, 0, 1) }}
                                                </div>
                                                <div class="mr-3">
                                                    <h6 class="mb-0 font-weight-bold">{{ $subject->name }}</h6>
                                                    <small
                                                        class="text-info font-weight-bold">{{ $subject->code ?? 'SUB-00' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5">
                                            <i class="fas fa-book-open fa-3x text-light mb-3"></i>
                                            <p class="text-muted">لم يتم تسجيل أي مقررات دراسية حتى الآن.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-light: #e8f5e9;
        }

        .bg-soft-info {
            background: #e3f2fd;
        }

        .bg-soft-warning {
            background: #fff3e0;
        }

        .icon-box-sm {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--secondary-color);
            text-transform: uppercase;
            margin-bottom: 10px;
            display: block;
        }

        .border-right-bold {
            border-right: 4px solid var(--primary-color);
        }

        .contact-card {
            transition: all 0.3s ease;
            background: #fff;
        }

        .contact-card:hover {
            border-color: #28a745 !important;
            transform: translateY(-2px);
        }

        .subject-card {
            background: #fff;
            border-radius: 12px;
            border-right: 5px solid #4e73df !important;
            transition: 0.3s;
        }

        .subject-icon {
            width: 45px;
            height: 45px;
            background: #f8f9fc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #4e73df;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }

        .nav-link {
            color: var(--secondary-color);
            font-weight: 600;
        }

        .status-indicator {
            position: absolute;
            bottom: 5px;
            right: 35%;
            width: 18px;
            height: 18px;
            border: 3px solid #fff;
            border-radius: 50%;
        }
    </style>
@endsection



<style>
    /* التنسيقات العامة للشاشة */
    :root {
        --primary-color: #4e73df;
        --secondary-color: #858796;
        --success-light: #e8f5e9;
    }

    .bg-soft-info {
        background: #e3f2fd;
    }

    .bg-soft-warning {
        background: #fff3e0;
    }

    .icon-box-sm {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--secondary-color);
        text-transform: uppercase;
        margin-bottom: 10px;
        display: block;
    }

    .border-right-bold {
        border-right: 4px solid var(--primary-color);
    }

    .contact-card {
        transition: all 0.3s ease;
        background: #fff;
    }

    .contact-card:hover {
        border-color: #28a745 !important;
        transform: translateY(-2px);
    }

    .subject-card {
        background: #fff;
        border-radius: 12px;
        border-right: 5px solid #4e73df !important;
    }

    .subject-icon {
        width: 45px;
        height: 45px;
        background: #f8f9fc;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #4e73df;
    }

    .nav-pills .nav-link.active {
        background-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
    }

    .nav-link {
        color: var(--secondary-color);
        font-weight: 600;
    }

    .status-indicator {
        position: absolute;
        bottom: 5px;
        right: 35%;
        width: 18px;
        height: 18px;
        border: 3px solid #fff;
        border-radius: 50%;
    }

    /* =============================================
       تنسيقات الطباعة الاحترافية
       ============================================= */
    @media print {

        /* 1. إخفاء كل العناصر التفاعلية والقوائم */
        .btn,
        .nav-pills,
        .navbar,
        .sidebar,
        footer,
        .breadcrumb,
        #printProfile {
            Display: none !important;
        }

        /* 2. إلغاء تأثير الـ Tabs وجعل المحتوى يظهر تحت بعضه */
        .tab-content>.tab-pane {
            Display: block !important;
            Opacity: 1 !important;
            Visibility: visible !important;
        }

        /* 3. تنسيق الورقة والحاويات */
        Body {
            Background-color: white !important;
            Font-size: 12pt;
            Color: #000;
        }

        .container-fluid {
            Width: 100% !important;
            Padding: 0 !important;
        }

        .col-xl-3,
        .col-xl-9,
        .col-lg-4,
        .col-lg-8 {
            Width: 100% !important;
            Max-width: 100% !important;
            Flex: 0 0 100% !important;
        }

        .card {
            Border: 1px solid #ddd !important;
            Box-shadow: none !important;
            Margin-bottom: 15px !important;
            Border-radius: 10px !important;
        }

        /* 4. إظهار "المقررات الدراسية" بشكل قائمة واضحة بدلاً من بطاقات */
        .subject-card {
            Border: 1px solid #eee !important;
            Break-inside: avoid;
        }

        /* 5. إضافة ترويسة للطباعة فقط (اختياري) */
        Body::before {
            Content: "تقرير بيانات الطالب الأكاديمية – {{ date('Y-m-d') }}";
            Display: block;
            Text-align: center;
            Font-weight: bold;
            Font-size: 1.2rem;
            Margin-bottom: 20px;
            Border-bottom: 2px solid #333;
            Padding-bottom: 10px;
        }

        /* تحسين توزيع العناوين */
        .section-title {
            Color: #333 !important;
            Border-bottom: 1px solid #eee;
            Padding-bottom: 5px;
            Margin-top: 15px;
        }

        
    }
</style>
