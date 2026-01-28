<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول | نظام المدرسة</title>

    <link rel="stylesheet" href="{{ asset('cms/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cms/dist/css/adminlte.min.css') }}">

    <style>
        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/cairo/static/Cairo-Regular.ttf') }}") format('truetype');
            font-weight: normal;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            /* خلفية هادئة تناسب النظام الأكاديمي */
            background-image: linear-gradient(135deg, #e9ecef 25%, transparent 25%), 
                              linear-gradient(225deg, #e9ecef 25%, transparent 25%), 
                              linear-gradient(45deg, #e9ecef 25%, transparent 25%), 
                              linear-gradient(315deg, #e9ecef 25%, #f4f6f9 25%);
            background-position: 10px 0, 10px 0, 0 0, 0 0;
            background-size: 20px 20px;
            background-repeat: repeat;
        }

        .login-box {
            width: 400px;
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
        }

        .card-header {
            background: transparent;
            border-bottom: none;
            padding-top: 30px;
        }

        .brand-logo {
            background: #2c3e50;
            color: #fff;
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 15px;
            box-shadow: 0 8px 15px rgba(44, 62, 80, 0.2);
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
            border: 1px solid #ced4da;
            text-align: right;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: none;
        }

        .input-group-text {
            border-radius: 0 10px 10px 0 !important;
            background-color: #f8f9fa;
        }

        .form-control-appended {
            border-radius: 10px 0 0 10px !important;
        }

        .btn-primary {
            background-color: #2c3e50;
            border-color: #2c3e50;
            border-radius: 10px;
            height: 45px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.15);
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="card">
        <div class="card-header text-center">
            <div class="brand-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3 class="font-weight-bold">تسجيل الدخول</h3>
            <p class="text-muted small">مرحباً بك في نظام المدرسة الإلكتروني</p>
        </div>
        
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success border-0 small text-right">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="post">
                @csrf
                
                <label class="small font-weight-bold text-secondary">الرقم المدرسي</label>
                <div class="input-group mb-3" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    <input type="text" name="school_id" value="{{ old('school_id') }}" 
                           class="form-control form-control-appended @error('school_id') is-invalid @enderror" 
                           placeholder="أدخل الرقم المدرسي" required autofocus>
                </div>
                @error('school_id')
                    <p class="text-danger small text-right mt-n2"><strong>{{ $message }}</strong></p>
                @enderror

                <label class="small font-weight-bold text-secondary">كلمة المرور</label>
                <div class="input-group mb-3" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <input type="password" name="password" 
                           class="form-control form-control-appended @error('password') is-invalid @enderror" 
                           placeholder="كلمة المرور" required>
                </div>
                @error('password')
                    <p class="text-danger small text-right mt-n2"><strong>{{ $message }}</strong></p>
                @enderror

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt ml-1"></i> دخول للنظام
                        </button>
                    </div>
                </div>
            </form>

            <div class="text-center mt-3">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-muted">نسيت كلمة المرور؟</a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="login-footer">
        &copy; {{ date('Y') }} نظام المدرسة الالكتروني <br>
    </div>
</div>

<script src="{{ asset('cms/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('cms/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('cms/dist/js/adminlte.min.js') }}"></script>

</body>
</html>