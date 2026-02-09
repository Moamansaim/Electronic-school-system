<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نظام المدرسة | @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('cms/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cms/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cms/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">

    <style>
        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/cairo/static/Cairo-Regular.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/cairo/static/Cairo-Bold.ttf') }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }


        body {
            font-family: 'Cairo', sans-serif;
        }

        /* تنسيق القائمة الجانبية */
        .main-sidebar {
            background-color: #2c3e50 !important;
            /* لون كحلي عصري بدلاً من الأسود */
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1) !important;
        }

        .brand-link {
            border-bottom: 1px solid #3e4f5f !important;
            text-align: center;
            font-weight: bold;
            padding: 20px 10px !important;
        }

        .nav-pills .nav-link {
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s;
        }

        .nav-pills .nav-link.active {
            background-color: #3498db !important;
            /* لون أزرق مريح */
            box-shadow: 0 4px 6px rgba(52, 152, 219, 0.3);
        }

        .nav-sidebar .nav-item:hover>.nav-link {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* تنسيق شريط التنقل العلوي */
        .main-header {
            border-bottom: none !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        /* تحسينات عامة للمحتوى */
        .content-wrapper {
            background-color: #f8fafc;
            /* خلفية افتح قليلاً */
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1rem;
        }

        /* تأثيرات التحميل (Loader) */
        .loader-wrapper {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            /* يظهر عند الحاجة عبر JS */
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light py-3">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link font-weight-600 text-dark">لوحة التحكم الرئيسية</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown px-3">
                    <form method="post" action="{{ route('logout') }}">
                        <button class="btn btn-danger btn-sm rounded-pill px-3" href="{{ route('logout') }}">
                            @csrf
                            <i class="fas fa-sign-out-alt ml-1"></i> تسجيل الخروج
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-bold">نظام المدرسة الالكتروني</span>
            </a>

            <div class="sidebar mt-3">
                <div class="user-panel pb-3 mb-3 d-flex align-items-center">
                    <div class="image pr-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-user-shield text-primary"></i>
                        </div>
                    </div>
                    @auth
                         <div class="info px-3 ">
                        @php
                            $teacher = Auth::user()->teacher;
                        @endphp
                        <a href="#" class="d-block font-weight-bold ">{{ $teacher ? $teacher->full_name : ' مستخدم نظام ' }}</a>
                    </div>
                    @endauth
                   
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                        <li class="nav-item">
                            <a href="#" class="nav-link active ">
                                <i class="nav-icon fas fa-th-large "></i>
                                <p>الرئيسية</p>
                            </a>
                        </li>

                        <li class="nav-header mt-3 text-uppercase small" style="color: #95a5a6;">إدارة المحتوى</li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard"></i>
                                <p>
                                    إدارة الصفوف الدراسية
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('grade_levels.index') }}" class="nav-link">
                                        <i class="fas fa-layer-group nav-icon"></i>
                                        <p>بيانات المراحل والصفوف</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('subjects.index') }}" class="nav-link">
                                        <i class="fas fa-book nav-icon"></i>
                                        <p>إدارة المواد الدراسية</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    إدارة المعلمين
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('teachers.index') }}" class="nav-link">
                                        <i class="fas fa-users-cog nav-icon"></i>
                                        <p>بيانات المعلمين</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    إدارة الطلاب
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('students.index') }}" class="nav-link">
                                        <i class="fas fa-user-edit nav-icon"></i>
                                        <p>بيانات الطلاب</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    إدارة الاختبارات الطلابية
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('exams.index') }}" class="nav-link">
                                        <i class="fas fa-tasks nav-icon"></i>
                                        <p>بيانات الاختبارات</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6 text-right">
                            <h1 class="m-0 font-weight-bold text-dark" style="font-size: 1.5rem;">@yield('title')</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <footer class="main-footer bg-white border-0 text-center py-3">
            <small class="text-muted">حقوق النشر &copy; 2026 <strong>نظام المدرسة الإلكتروني</strong>. جميع الحقوق
                محفوظة.</small>
        </footer>
    </div>

    <script src="{{ asset('cms/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('cms/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('cms/dist/js/adminlte.min.js') }}"></script>
    @stack('script')
</body>

</html>