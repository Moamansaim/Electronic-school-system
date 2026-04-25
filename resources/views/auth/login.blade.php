<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول | نظام المدرسة الإلكتروني</title>
    <link rel="stylesheet" href="{{ asset('cms/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cms/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

        body {
            font-family: 'Cairo', sans-serif;
            background: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            width: 400px;
        }

        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }

        .brand-logo {
            background: #2c3e50;
            color: #fff;
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
        }

        h4.text-center {
            color: #2c3e50;
        }

        .form-control {
            border-radius: 10px;
            height: 48px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 5px rgba(44, 62, 80, 0.3);
        }

        .btn-primary {
            background: #2c3e50;
            border: none;
            border-radius: 10px;
            height: 48px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background: #34495e;
        }

        .btn-success {
            background: #27ae60;
            border: none;
            border-radius: 10px;
            height: 48px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-success:hover {
            background: #2ecc71;
        }

        .modal-content {
            border-radius: 20px !important;
            border: none;
            padding: 20px;
        }

        .alert-info {
            background: #eef3f7;
            color: #2c3e50;
            border-radius: 10px;
            padding: 10px;
        }

        a {
            color: #2c3e50;
        }

        a:hover {
            color: #34495e;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <div class="card p-4">
            <div class="brand-logo"><i class="fas fa-graduation-cap"></i></div>
            <h4 class="text-center font-weight-bold">تسجيل الدخول</h4>
            <p class="text-center text-muted small mb-4">نظام إدارة المدرسة الذكي</p>

            <!-- نموذج تسجيل الدخول -->
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" name="school_id" class="form-control @error('school_id') is-invalid @enderror"
                        placeholder="الرقم المدرسي" required>
                    @error('school_id')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="كلمة المرور" required>
                    @error('password')
                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" id="rem">
                        <label class="custom-control-label small" for="rem">تذكرني</label>
                    </div>
                    <a href="#" data-toggle="modal" data-target="#modal-forgot" class="small font-weight-bold">نسيت كلمة
                        المرور؟</a>
                </div>
                <button type="submit" class="btn btn-primary btn-block">دخول للنظام</button>
            </form>
        </div>
        <div class="text-center mt-3 text-muted small">&copy; {{ date('Y') }} جميع الحقوق محفوظة</div>
    </div>

    <!-- modal استعادة كلمة المرور -->
    <div class="modal fade" id="modal-forgot" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="font-weight-bold">استعادة الحساب</h5>
                </div>
                <div class="modal-body">
                    <!-- الخطوة 1: التحقق من الهوية -->
                    <div id="step-1">
                        <p class="text-muted small">يرجى إدخال البيانات للتحقق من هويتك:</p>
                        <input type="text" id="u_school_id" class="form-control mb-2" placeholder="الرقم المدرسي">
                        <input type="text" id="u_national_id" class="form-control mb-3" placeholder="رقم الهوية">
                        <button type="button" id="btn-verify" class="btn btn-primary btn-block">التحقق من
                            البيانات</button>
                    </div>

                    <!-- الخطوة 2: إعادة ضبط كلمة المرور -->
                    <div id="step-2" style="display:none;">
                        <div class="alert alert-info small" id="phone-hint-msg"></div>
                        <input type="text" id="full_phone" class="form-control mb-2"
                            placeholder="رقم الهاتف كاملاً (مثال: 05XXXXXXXX)">
                        <input type="password" id="new_password" class="form-control mb-2"
                            placeholder="كلمة المرور الجديدة">
                        <input type="password" id="confirm_password" class="form-control mb-3"
                            placeholder="تأكيد كلمة المرور">
                        <button type="button" id="btn-reset" class="btn btn-success btn-block">حفظ كلمة المرور</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery و Bootstrap -->
    <script src="{{ asset('cms/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('cms/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>

    <script>
        // إعداد الـ CSRF لكل AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // دالة موحدة لإظهار Toast في أعلى يمين الشاشة
        function notify(message, isSuccess = true) {
            Swal.close();
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: isSuccess ? 'success' : 'error',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: isSuccess ? '#28a745' : '#dc3545',
                color: '#fff'
            });
        }

        // --- التحقق من الهوية (الخطوة الأولى) ---
        $('#btn-verify').click(function () {
            $.post("{{ url('password/verify-identity') }}", {
                school_id: $('#u_school_id').val(),
                national_id: $('#u_national_id').val()
            }).done(function (res) {
                // لا يوجد تنبيه هنا، ننتقل مباشرة للخطوة الثانية
                $('#phone-hint-msg').text('رقم الهاتف المسجل يبدأ بـ ' + res.phone_hint);
                $('#step-1').fadeOut(200, function () { $('#step-2').fadeIn(); });
            }).fail(function (xhr) {
                // الخطأ فقط يظهر تنبيه
                notify(xhr.responseJSON?.message || 'بيانات غير صحيحة', false);
            });
        });

        // --- إعادة ضبط كلمة المرور (الخطوة الثانية) ---
        $('#btn-reset').click(function () {
            // التحقق من مطابقة الباسورد
            if ($('#new_password').val() !== $('#confirm_password').val()) {
                notify('كلمة المرور وتأكيدها غير متطابقين!', false);
                return;
            }

            $.post("{{ route('password.reset') }}", {
                school_id: $('#u_school_id').val(),
                full_phone: $('#full_phone').val(),
                password: $('#new_password').val(),
                password_confirmation: $('#confirm_password').val()
            }).done(function (res) {
                // نجاح: يظهر تنبيه واحد فقط
                notify('تم تسجيل الدخول بنجاح');
                setTimeout(() => { window.location.href = res.redirect; }, 2000);
            }).fail(function (xhr) {
                // خطأ: يظهر تنبيه واحد فقط
                notify(xhr.responseJSON?.message || 'حدث خطأ في النظام', false);
            });
        });

    </script>

</body>

</html>