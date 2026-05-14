<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نظام المدرسة | @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('cms/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cms/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cms/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
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

        .main-sidebar {
            background-color: #2c3e50 !important;
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
            box-shadow: 0 4px 6px rgba(52, 152, 219, 0.3);
        }

        .nav-sidebar .nav-item:hover>.nav-link {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .main-header {
            border-bottom: none !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .content-wrapper {
            background-color: #f8fafc;
        }

        /* تنسيقات الإشعارات */
        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            padding: 2px 5px;
            border-radius: 50%;
            background: #ff3e1d;
            color: white;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #fff;
        }

        .notification-dropdown {
            width: 320px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
            padding: 0;
        }

        .notification-header {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f8f9fa;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none !important;
            color: #333 !important;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #f0f7ff;
        }

        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e7e7ff;
            color: #696cff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 12px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light py-3">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link font-weight-600 text-dark">لوحة التحكم الرئيسية</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto align-items-center">
                <!-- جرس الإشعارات -->
                <li class="nav-item dropdown px-2">
                    <a class="nav-link position-relative" href="#" data-toggle="dropdown">
                        <i class="far fa-bell" style="font-size: 1.2rem;"></i>
                        @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                            <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-right notification-dropdown">
                        <div class="notification-header text-right">
                            <span>الإشعارات</span>
                            <a href="#" class="small text-primary">تحديد الكل كمقروء</a>
                        </div>

                        <div class="notification-list text-right">
                            @if(auth()->check())
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="notification-item unread">
                                        <div class="icon-circle">
                                            <i class="fas fa-envelope-open-text"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1" style="font-size: 13px; font-weight: bold;">{{ $notification->data['title'] }}</h6>
                                            <small class="text-muted d-block" style="font-size: 12px;">{{ $notification->data['body'] }}</small>
                                            <small class="text-primary mt-1 d-block" style="font-size: 10px;">
                                                <i class="far fa-clock ml-1"></i>{{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-muted">
                                        <i class="far fa-bell-slash d-block mb-2" style="font-size: 1.5rem;"></i>
                                        لا توجد إشعارات جديدة
                                    </div>
                                @endforelse
                            @endif
                        </div>

                        <div class="p-2 border-top text-center">
                            <a href="#" class="small text-muted">عرض كل الإشعارات</a>
                        </div>
                    </div>
                </li>

                <li class="nav-item px-3">
                    <button type="button" class="btn btn-danger btn-sm rounded-pill px-3" data-toggle="modal"
                        data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt ml-1"></i> تسجيل الخروج
                    </button>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
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
                        <div class="info px-3">
                            @php $user = Auth::user()->teacher ?? Auth::user()->student; @endphp
                            <a href="#" class="d-block font-weight-bold text-white"
                                style="font-size:14px">{{ $user ? $user->full_name : ' مستخدم نظام ' }}</a>
                        </div>
                    @endauth
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <!-- الرئيسية -->
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-home"></i>
                                <p>الرئيسية</p>
                            </a>
                        </li>
                        
                        <li class="nav-header mt-3 text-uppercase small" style="color: #95a5a6;">إدارة المحتوى</li>

                        <!-- إدارة الصفوف -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-school"></i>
                                <p>إدارة الصفوف الدراسية<i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="{{ route('grade_levels.index') }}" class="nav-link"><i
                                            class="fas fa-layer-group nav-icon"></i>
                                        <p>بيانات المراحل والصفوف</p>
                                    </a></li>
                                <li class="nav-item"><a href="{{ route('subjects.index') }}" class="nav-link"><i
                                            class="fas fa-journal-whills nav-icon"></i>
                                        <p>إدارة المواد الدراسية</p>
                                    </a></li>
                            </ul>
                        </li>

                        <!-- إدارة المعلمين -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>إدارة المعلمين<i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="{{ route('teachers.index') }}" class="nav-link"><i
                                            class="fas fa-users-cog nav-icon"></i>
                                        <p>بيانات المعلمين</p>
                                    </a></li>
                            </ul>
                        </li>

                        <!-- إدارة الطلاب -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>إدارة الطلاب<i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="{{ route('students.index') }}" class="nav-link"><i
                                            class="fas fa-users nav-icon"></i>
                                        <p>بيانات الطلاب</p>
                                    </a></li>
                            </ul>
                        </li>

                        <!-- إدارة الاختبارات -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-paste"></i>
                                <p>إدارة الاختبارات الطلابية<i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="{{ route('exams.index') }}" class="nav-link"><i
                                            class="fas fa-pen-nib nav-icon"></i>
                                        <p>بيانات الاختبارات</p>
                                    </a></li>
                                <li class="nav-item">
                                    <a href="{{ route('exam-schedules.index') }}" class="nav-link">
                                        <i class="fas fa-list-ol nav-icon"></i>
                                        <p>جدول الاختبارات</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- الحضور والغياب -->
                        <li class="nav-item">
                            <a href="{{ route('attendance.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-clock"></i>
                                <p>إدارة الحضور والغياب</p>
                            </a>
                        </li>

                        <li class="nav-header mt-3 text-uppercase small" style="color: #95a5a6;">بوابة الطالب</li>

                        <!-- الاختبارات المتاحة -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-laptop-code"></i>
                                <p>الاختبارات الطلابية<i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('exams.student') }}" class="nav-link">
                                        <i class="fas fa-clipboard-list nav-icon"></i>
                                        <p>الاختبارات المتاحة </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('student.schdule.exam') }}" class="nav-link">
                                        <i class="nav-icon fas fa-calendar-check"></i>
                                        <p>جدول الاختبارات </p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <!-- الجدول الدراسي -->
                        <li class="nav-item">
                            <a href="{{ route('students.show-schedule') }}" class="nav-link">
                                <i class="nav-icon fas fa-table"></i>
                                <p>الجدول الدراسي</p>
                            </a>
                        </li>

                        <!-- البيانات الفصلية -->
                        <li class="nav-item">
                            <a href="{{ route('exams.student.marks') }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p> نتائج الاختبارات</p>
                            </a>
                        </li>

                        <!-- الملخصات -->
                        <li class="nav-item">
                            <a href="{{ route('student.files.summary') }}" class="nav-link">
                                <i class="nav-icon fas fa-file-download"></i>
                                <p>الملخصات التعليمية</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content -->
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
            <small class="text-muted">حقوق النشر &copy; {{ date('Y') }} <strong>نظام المدرسة الإلكتروني</strong>. جميع الحقوق محفوظة.</small>
        </footer>
    </div>

    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-right">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bold">تأكيد تسجيل الخروج</h5>
                </div>
                <div class="modal-body">هل أنت متأكد أنك تريد مغادرة النظام؟</div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger"
                        onclick="document.getElementById('logout-form').submit();">نعم، تسجيل الخروج</button>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="{{ asset('cms/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('cms/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('cms/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    @stack('script')
</body>

</html>