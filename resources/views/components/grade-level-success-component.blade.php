<div class="container-fluid mt-3">
    @if (session()->has('success'))

        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session()->get('success') }}
        </div>

    @endif
</div>