<div class="container-fluid mt-3">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert"
            style="background-color: #f31e1e; color: #d4edda; border-right: 5px solid #fa3131d2; width: fit-content; min-width: 300px;">

            <div class="d-flex align-items-center">
                {{-- أيقونة النجاح --}}
                <i class="far fa-times-circle mr-2"></i>

                <span class="font-weight-bold mr-2">
                    {{ session()->get('error') }}
                </span>
            </div>

            {{-- زر الإغلاق (الأكس) --}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="outline: none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>