@extends('layout-cms.main-layout')
@section('title', 'رفع الملخصات والمستندات التعليمية')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-4">
                    <h4 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-file-upload text-primary mr-2"></i> إرفاق مستندات لمادة: {{ $subject->name }}
                    </h4>
                </div>

                <div class="card-body p-4">
                    <div id="drop-zone"
                        class="d-flex flex-column align-items-center justify-content-center p-5 border rounded"
                        style="border: 2px dashed #007bff; background-color: #f8f9fa; cursor: pointer; transition: 0.3s;">
                        <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                        <h5 class="text-secondary">اسحب الملفات هنا أو اضغط للاختيار</h5>
                        <small class="text-muted">PDF, Images, Word (DOCX)</small>
                    </div>

                    <input type="file" id="file-input" multiple style="display: none;">

                    <div id="file-list" class="mt-4"></div>

                    <input type="hidden" id="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" id="subject_id" value="{{ $subject->id }}">
                </div>

                <div class="card-footer bg-white border-0 text-right">
                    <button type="button" class="btn btn-success px-4 py-2 rounded-pill" id="uploadBtn">
                        <i class="fas fa-check-circle mr-1"></i> رفع الملفات الآن
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const fileList = document.getElementById('file-list');
        let filesArray = [];

        // --- دالة التنبيه (خارج دالة النقر لتعمل في أي وقت) ---
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

        // تفعيل الضغط والسحب
        dropZone.onclick = () => fileInput.click();
        fileInput.onchange = (e) => handleFiles(e.target.files);
        dropZone.ondragover = (e) => { e.preventDefault(); dropZone.style.background = "#e7f1ff"; };
        dropZone.ondragleave = () => { dropZone.style.background = "#f8f9fa"; };
        dropZone.ondrop = (e) => { e.preventDefault(); dropZone.style.background = "#f8f9fa"; handleFiles(e.dataTransfer.files); };

        function handleFiles(files) {
            for (let file of files) { filesArray.push(file); }
            renderFiles();
        }

        function renderFiles() {
            fileList.innerHTML = '';
            filesArray.forEach((file, index) => {
                let div = document.createElement('div');
                div.className = "d-flex justify-content-between align-items-center p-3 mb-2 bg-light border rounded";
                div.innerHTML = `
                    <div>
                        <i class="fas fa-file-alt text-primary mr-2"></i>
                        <span class="font-weight-bold">${file.name}</span>
                        <small class="text-muted ml-2">(${(file.size / 1024).toFixed(1)} KB)</small>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" onclick="removeFile(${index})">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                `;
                fileList.appendChild(div);
            });
        }

        function removeFile(index) {
            filesArray.splice(index, 1);
            renderFiles();
        }

        // --- دالة الرفع ---
        document.getElementById('uploadBtn').onclick = function () {
            if (filesArray.length === 0) {
                notify('يرجى اختيار ملفات أولاً', false);
                return;
            }

            let formData = new FormData();
            filesArray.forEach(file => formData.append('files[]', file));
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('user_id', document.getElementById('user_id').value);
            formData.append('subject_id', document.getElementById('subject_id').value);

            fetch("{{ route('files.store') }}", {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        notify('تم رفع الملفات بنجاح!', true);
                        setTimeout(() => { location.reload(); }, 1500);
                    } else {
                        let errorMsg = data.errors ? Object.values(data.errors)[0][0] : "حدث خطأ أثناء الرفع";
                        notify(errorMsg, false);
                    }
                })
                .catch(err => {
                    notify('فشل الاتصال بالسيرفر', false);
                });
        };
    </script>
@endpush