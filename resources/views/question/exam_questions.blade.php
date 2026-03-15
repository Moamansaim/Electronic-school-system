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
                            <i class="fas fa-graduation-cap text-primary ml-2"></i> قائمة أسئلة اختبار
                            {{ $exam->subject->name ?? '' }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="accordion" id="questionsAccordion">
                    @forelse ($questions as $question)
                                    <div class="card mb-3 border-0 shadow-sm" style="border-radius: 10px;">
                                        <div class="card-header bg-light py-3" id="heading{{ $question->id }}"
                                            style="border-radius: 10px !important;">
                                            <button
                                                class="btn btn-block text-right d-flex justify-content-between align-items-center text-dark font-weight-bold"
                                                type="button" data-toggle="collapse" data-target="#collapse{{ $question->id }}"
                                                aria-expanded="false">
                                                <span>{{ $loop->iteration }}. {{ $question->question_text }}</span>
                                                <i class="fas fa-chevron-down text-muted"></i>
                                            </button>
                                        </div>

                                        <div id="collapse{{ $question->id }}" class="collapse" data-parent="#questionsAccordion">
                                            <div class="card-body border-top">
                                                <div class="row">
                                                    <div class="col-md-4"><strong>النوع:</strong> <span class="badge badge-info">
                                                            {{ match ($question->question_type) {
                                                            'multiple_choice' => 'خيار من متعدد',
                                                            'essay_question' => 'مقالي',
                                                            default => $question->question_type,
                                                        } }}
                                                        </span></div>
                                                    <div class="col-md-4"><strong>الدرجة:</strong> {{ $question->mark }} درجات</div>
                                                    <div class="col-md-4 text-muted small">تاريخ الإضافة:
                                                        {{ $question->created_at->format('Y-m-d') }}
                                                    </div>
                                                </div>

                                                @if($question->options && $question->options->count() > 0)
                                                    <div class="mt-3 p-3 bg-light rounded">
                                                        <strong>الخيارات:</strong>
                                                        <ul class="mb-0">
                                                            @foreach($question->options as $option)
                                                                <li class="{{ $option->is_correct ? 'text-success font-weight-bold' : '' }}">
                                                                    {{ $option->option_text }} {{ $option->is_correct ? '(الإجابة الصحيحة)' : '' }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                <div class="d-flex justify-content-end mt-3" style="gap: 10px;">
                                                    <a href="{{ route('questions.edit', $question->id) }}"
                                                        class="btn btn-sm btn-info">تعديل</a>
                                                    <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                        data-target="#deleteModal{{ $question->id }}">حذف</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- المودال الخاص بالحذف --}}
                                    <div class="modal fade" id="deleteModal{{ $question->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                                <div class="modal-body p-5 text-center">
                                                    <div class="text-danger mb-4"><i class="fas fa-exclamation-circle fa-4x"></i></div>
                                                    <h3>تأكيد الحذف</h3>
                                                    <p class="text-muted">هل أنت متأكد من حذف سؤال ({{ $question->question_text }})؟</p>
                                                    <div class="d-flex justify-content-center mt-4" style="gap: 10px;">
                                                        <button type="button" class="btn btn-light px-4" data-dismiss="modal">إلغاء</button>
                                                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-danger px-4">تأكيد الحذف</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    @empty
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80"
                                class="mb-3 opacity-50">
                            <p class="text-muted font-weight-bold">لم يتم العثور على أي أسئلة حالياً.</p>
                        </div>
                    @endforelse
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
        .accordion .btn[aria-expanded="true"] i {
            transform: rotate(180deg);
        }

        .accordion .btn {
            transition: all 0.3s;
        }

        .accordion .btn:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection