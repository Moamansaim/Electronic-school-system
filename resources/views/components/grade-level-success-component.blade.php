<div>
<div class="container-fluid mt-3">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" 
             style="background-color: #d4edda; color: #155724; border-right: 5px solid #28a745; width: fit-content; min-width: 300px;">
            
            <div class="d-flex align-items-center">
                {{-- أيقونة النجاح --}}
                <i class="fas fa-check-circle mr-2" style="font-size: 1.2rem;"></i>
                
                <span class="font-weight-bold mr-2">
                    {{ session()->get('success') }}
                </span>
            </div>

            {{-- زر الإغلاق (الأكس) --}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="outline: none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>
