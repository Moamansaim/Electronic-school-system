@extends('layout-cms.main-layout')
@section('title', 'الملخصات')

@section('content')
<div class="container py-5">
    <div class="row">
        @forelse($summaries as $summary)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm summary-card">
                    <div class="card-header bg-white border-0 text-center pt-4">
                        <div class="icon-circle shadow-sm mx-auto {{ str_contains($summary->file_name, '.pdf') ? 'pdf-bg' : 'img-bg' }}">
                            @if(str_contains($summary->file_name, '.pdf'))
                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                            @else
                                <i class="fas fa-file-image fa-2x text-primary"></i>
                            @endif
                        </div>
                    </div>

                    <div class="card-body text-center">
                        <h6 class="fw-bold text-dark mb-2">{{ $summary->file_name }}</h6>
                        <p class="text-muted small mb-3">
                            <i class="far fa-clock me-1"></i> تم الرفع: {{ $summary->created_at->format('Y-m-d') }}
                        </p>
                        
                        <div class="d-grid shadow-sm p-3">
                            <a href="{{ asset('storage/' . $summary->file_path) }}" target="_blank" class="btn btn-primary rounded-pill">
                                <i class="fas fa-download me-2"></i> تحميل الملخص
                            </a>
                        </div>
                    </div>
                    
                  
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="alert alert-info">لا توجد ملخصات متاحة حالياً لهذه المادة.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection

<style>
    /* تحسينات التصميم */
    .summary-card {
        transition: all 0.3s ease;
        border-radius: 20px !important;
        overflow: hidden;
    }

    .summary-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }

    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .pdf-bg { background-color: #fff5f5; }
    .img-bg { background-color: #f0f7ff; }

    .btn-primary {
        background-color: #4e73df;
        border: none;
        padding: 10px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
    }
</style>
