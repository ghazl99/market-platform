// Product Create Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    const imageInput = document.getElementById('productImages');
    const imagePreview = document.getElementById('imagePreview');
    const saveDraftBtn = document.getElementById('saveDraftBtn');

    // Image upload preview
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            imagePreview.innerHTML = '';

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';
                        previewItem.innerHTML = `
                            <img src="${e.target.result}" class="preview-image" alt="Preview ${index + 1}">
                            <button type="button" class="preview-remove" onclick="removeImage(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        imagePreview.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Basic validation
            const requiredFields = ['name', 'price', 'categories'];
            let isValid = true;

            requiredFields.forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input && !input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else if (input) {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                if (typeof showError === 'function') {
                    showError('يرجى ملء جميع الحقول المطلوبة');
                } else {
                    alert('يرجى ملء جميع الحقول المطلوبة');
                }
                return;
            }

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            submitBtn.disabled = true;

            // Get form data
            const formData = new FormData(form);

            // Submit form via fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof showSuccess === 'function') {
                        showSuccess(data.message);
                    } else {
                        alert(data.message);
                    }
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard.product.index") }}';
                    }, 1000);
                } else {
                    if (typeof showError === 'function') {
                        showError(data.message || 'حدث خطأ');
                    } else {
                        alert(data.message || 'حدث خطأ');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showError === 'function') {
                    showError('حدث خطأ');
                } else {
                    alert('حدث خطأ');
                }
            })
            .finally(() => {
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Save as draft functionality
    if (saveDraftBtn) {
        saveDraftBtn.addEventListener('click', function() {
            // Add draft status to form
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'draft';
            form.appendChild(statusInput);

            // Show loading state
            const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            this.disabled = true;

            // Get form data
            const formData = new FormData(form);

            // Submit form via fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof showSuccess === 'function') {
                        showSuccess(data.message);
                    } else {
                        alert(data.message);
                    }
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard.product.index") }}';
                    }, 1000);
                } else {
                    if (typeof showError === 'function') {
                        showError(data.message || 'حدث خطأ');
                    } else {
                        alert(data.message || 'حدث خطأ');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showError === 'function') {
                    showError('حدث خطأ');
                } else {
                    alert('حدث خطأ');
                }
            })
            .finally(() => {
                // Reset button
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }

    // Real-time validation
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Drag and drop functionality for file upload
    const fileUploadLabel = document.querySelector('.file-upload-label');
    if (fileUploadLabel) {
        fileUploadLabel.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        fileUploadLabel.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        fileUploadLabel.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');

            const files = Array.from(e.dataTransfer.files);
            const fileInput = document.getElementById('productImages');

            // Create a new FileList
            const dt = new DataTransfer();
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    dt.items.add(file);
                }
            });

            fileInput.files = dt.files;

            // Trigger change event
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        });
    }
});

function removeImage(index) {
    const previewItems = document.querySelectorAll('.preview-item');
    if (previewItems[index]) {
        previewItems[index].remove();
    }
}

// Auto-save functionality (optional) - disabled for now
// function autoSave() {
//     const form = document.getElementById('productForm');
//     if (!form) return;

//     const formData = new FormData(form);
//     formData.append('status', 'draft');
//     formData.append('auto_save', '1');

//     fetch(form.action, {
//         method: 'POST',
//         body: formData,
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         }
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             console.log('Auto-saved successfully');
//         }
//     })
//     .catch(error => {
//         console.error('Auto-save error:', error);
//     });
// }

// Set up auto-save every 30 seconds - disabled for now
// setInterval(autoSave, 30000);
