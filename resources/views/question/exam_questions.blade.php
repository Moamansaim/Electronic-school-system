@extends('layout-cms.main-layout')
@section('title', 'قائمة الأسئلة')

@section('content')
    <div class="" dir="rtl">
        <x-grade-level-success-component />
        <x-grade-level-error-component />
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="mb-0 font-weight-bold text-dark">
                            <i class="fas fa-graduation-cap text-primary ml-2"></i>  قائمة أسئلة اختبار  {{ $exam->subject->name }}
                        </h4>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-wrap justify-content-end align-items-center" style="gap: 10px;">

                            {{-- نموذج البحث --}}
                            <form action="{{ route('exams.index') }}" method="GET" class="ml-2">
                                <div class="input-group border rounded-pill px-2 py-1 bg-light shadow-sm">
                                    <input type="text" name="search" class="form-control bg-transparent border-0"
                                        placeholder="ابحث عن سؤال..." value="{{ request('search') }}" style="width: 180px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-link text-muted p-0 px-2" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table  table-hover  align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0  py-3" style="width: 60px;">#</th>
                                <th class="border-0 text-muted py-3 ">السؤال</th>
                                <th class="border-0 text-muted py-3 ">نوع السؤال</th>
                                <th class="border-0 text-muted py-3 "> درجة السؤال </th>
                                <th class="border-0 text-muted py-3 "> تاريخ الإضافة </th>
                                <th class="border-0 text-muted py-3 "> تاريخ التعديل </th>
                                <th class="border-0  text-muted py-3" style="width: 150px;">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($questions as $question)
                                <tr>
                                    <td class=" font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                    <td class=" text-muted">
                                        {{ $question->question_text }}
                                    </td>
                                    <td class=" text-muted">
                                        {{ $question->question_type }} 
                                    </td>
                                    <td class=" text-muted">
                                        {{ $question->mark }} درجات
                                    </td>
                                    <td class=" text-muted">
                                        {{ $question->created_at }}
                                    </td>
                                    <td class=" text-muted">
                                        {{ $question->updated_at }} 
                                    </td>
                
                                    <td>
                                        <div class="d-flex justify-content-center" style="gap: 3px;">
                                            <a href="{{ route('questions.edit', $question->id) }}"
                                                class="btn btn-sm btn-info mr-2 d-flex align-items-center" title="تعديل">
                                                <i class="fas fa-pen mr-1"></i>
                                                <span>تعديل</span>
                                            </a>
                                            <button class="btn btn-sm btn-danger d-flex align-items-center" data-toggle="modal"
                                                data-target="#deleteModal{{ $question->id }}" title="حذف">
                                                <i class="fas fa-trash-alt mr-1"></i>
                                                <span>حذف</span>
                                            </button>
                                        </div>
                                        {{-- مودل الحذف --}}
                                        <div class="modal fade" id="deleteModal{{ $question->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                                    <div class="modal-body p-5 ">
                                                        <div class="text-danger mb-4">
                                                            <i class="fas fa-exclamation-circle fa-4x"></i>
                                                        </div>
                                                        <h3 class="font-weight-bold">تأكيد الحذف</h3>
                                                        <p class="text-muted">هل أنت متأكد من حذف سؤال
                                                            <strong>({{ $question->question_text}})</strong>؟<br>
                                                        </p>
                                                        <div class="d-flex justify-content-center mt-4" style="gap: 10px;">
                                                            <button type="button"
                                                                class="btn btn-light px-4 rounded-pill shadow-sm font-weight-bold"
                                                                data-dismiss="modal">إلغاء</button>
                                                            <form action="{{ route('questions.destroy', $question->id) }}"
                                                                method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger px-4 rounded-pill shadow-sm font-weight-bold">تأكيد
                                                                    الحذف</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-5 ">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                            class="mb-3 opacity-50">
                                        <p class="text-muted font-weight-bold">لم يتم العثور على أي أسئلة حالياً.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($questions->hasPages())
                <div class="card-footer bg-white border-top-0 py-4 d-flex justify-content-center">
                    {{ $questions->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        /* محاذاة عامة للجدول */
        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        /* استثناء عمود اسم المرحلة (يحتوي أيقونة + نص) */
        .table tbody td:nth-child(2) {
            text-align: right;
        }

        /* تحسين مظهر الأزرار */
        .btn-outline-info:hover,
        .btn-outline-secondary:hover,
        .btn-outline-success:hover {
            color: white !important;
        }

        /* توحيد الأيقونات الدائرية */
        .rounded-circle {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        /* توحيد المسافات بين الأزرار */
        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endsection