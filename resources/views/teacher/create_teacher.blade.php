@extends('layout-cms.main-layout')
@section('title', 'إضافة معلم جديد')
@section('content')
    <div class="p-4">
        <x-grade-level-error-component />

        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary font-weight-bold">
                    <i class="fas fa-user-plus mr-2"></i>      إضافة معلم جديد

                </h5>
            </div>

            <form action="{{ route('teachers.store') }}" method="POST">
                @csrf
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">اسم المعلم </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="first_name" value="{{ old('first_name') }}"
                                    class="form-control border-left-0 @error('first_name') is-invalid @enderror"
                                    placeholder="أدخل اسم المعلم">
                            </div>
                            @error('first_name')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">اسم الأب </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-user-friends text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="father_name" value="{{ old('father_name') }}"
                                    class="form-control border-left-0 @error('father_name') is-invalid @enderror"
                                    placeholder="أدخل اسم الأب">
                            </div>
                            @error('father_name')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">اسم الجد </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-users text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="grandfather_name" value="{{ old('grandfather_name') }}"
                                    class="form-control border-left-0 @error('grandfather_name') is-invalid @enderror"
                                    placeholder="أدخل اسم الجد">
                            </div>
                            @error('grandfather_name')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">اسم العائلة </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-id-card-alt text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="family_name" value="{{ old('family_name') }}"
                                    class="form-control border-left-0 @error('family_name') is-invalid @enderror"
                                    placeholder="أدخل اسم العائلة">
                            </div>
                            @error('family_name')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">تاريخ الميلاد </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                    </span>
                                </div>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                    class="form-control border-left-0 @error('date_of_birth') is-invalid @enderror">
                            </div>
                            @error('date_of_birth')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label font-weight-bold mb-2">رقم الهوية </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-fingerprint text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="national_id" value="{{ old('national_id') }}"
                                    class="form-control border-left-0 @error('national_id') is-invalid @enderror"
                                    placeholder="أدخل رقم الهوية">
                            </div>
                            @error('national_id')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold mb-2">المدينة </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-city text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="city" value="{{ old('city') }}"
                                    class="form-control border-left-0 @error('city') is-invalid @enderror"
                                    placeholder="أدخل اسم المدينة">
                            </div>
                            @error('city')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold mb-2">المنطقة السكنية / الحي </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="district" value="{{ old('district') }}"
                                    class="form-control border-left-0 @error('district') is-invalid @enderror"
                                    placeholder="أدخل اسم الحي ">
                            </div>
                            @error('district')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label font-weight-bold mb-2">اسم الشارع</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0">
                                        <i class="fas fa-road text-muted"></i>
                                    </span>
                                </div>
                                <input type="text" name="street" value="{{ old('street') }}"
                                    class="form-control border-left-0 @error('street') is-invalid @enderror"
                                    placeholder="أدخل اسم الشارع">
                            </div>
                            @error('street')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label font-weight-bold mb-2">أرقام الجوال</label>
                            <div id="phone-container">
                                <div class="input-group mb-2 phone-item">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0">
                                            <i class="fas fa-mobile-alt text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="phone_numbers[]"
                                        class="form-control border-left-0 @error('phone_numbers.*') is-invalid @enderror "
                                        placeholder="أدخل رقم الجوال">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success add-phone">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('phone_numbers.*')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('teachers.index') }}" class="text-muted">
                        <i class="fas fa-arrow-right mr-1"></i> العودة للقائمة
                    </a>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-save ml-2"></i> حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const container = document.getElementById('phone-container');



        // عند الضغط على زر الإضافة

        container.addEventListener('click', function(e) {

            if (e.target.closest('.add-phone')) {

                const newItem = document.createElement('div');

                newItem.className = 'input-group mb-2 phone-item';

                newItem.innerHTML = `

                    <div class="input-group-prepend">

                        <span class="input-group-text bg-light border-right-0">

                            <i class="fas fa-phone text-muted"></i>

                        </span>

                    </div>

                    <input type="text" name="phone_numbers[]" class="form-control border-left-0" placeholder="أدخل رقم جوال إضافي">

                    <div class="input-group-append">

                        <button type="button" class="btn btn-danger remove-phone">

                            <i class="fas fa-trash-alt"></i>

                        </button>

                    </div>

                `;

                container.appendChild(newItem);

            }



            // عند الضغط على زر الحذف

            if (e.target.closest('.remove-phone')) {

                e.target.closest('.phone-item').remove();

            }

        });

    });
</script>
