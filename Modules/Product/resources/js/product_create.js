// Product Create Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Product create script loaded');

    // Check if notifications are available
    if (typeof showSuccess === 'function') {
        console.log('Notifications system is available');
    } else {
        console.log('Notifications system is NOT available');
    }

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
        let isSubmitting = false; // Flag to prevent double submission

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Always prevent default form submission

            // منع الإرسال المتعدد
            if (isSubmitting) {
                console.log('Form is already being submitted, ignoring...');
                return;
            }

            // Basic validation
            const requiredFields = ['name', 'price', 'category', 'stock_quantity', 'description'];
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
                    showError('خطأ في التحقق', 'يرجى ملء جميع الحقول المطلوبة');
                } else {
                    alert('يرجى ملء جميع الحقول المطلوبة');
                }
                return;
            }

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');

            // منع الإرسال المتعدد
            if (submitBtn.disabled) {
                return;
            }

            isSubmitting = true; // Set flag
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            submitBtn.disabled = true;

            // إضافة timeout لمنع التعليق الدائم
            const timeoutId = setTimeout(() => {
                console.log('Request timeout, resetting button...');
                isSubmitting = false;
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                if (typeof showError === 'function') {
                    showError('خطأ', 'انتهت مهلة الطلب، يرجى المحاولة مرة أخرى');
                } else {
                    alert('انتهت مهلة الطلب، يرجى المحاولة مرة أخرى');
                }
            }, 30000); // 30 seconds timeout

            // إرسال البيانات عبر fetch
            const formData = new FormData(form);

            // إضافة CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('_token', csrfToken);

            // إضافة البيانات المطلوبة إذا لم تكن موجودة
            if (!formData.get('status')) {
                formData.append('status', 'active');
            }
            if (!formData.get('is_active')) {
                formData.append('is_active', '1');
            }
            if (!formData.get('is_featured')) {
                formData.append('is_featured', '0');
            }

            // Log form data
            console.log('Form action:', form.action);
            console.log('CSRF token:', csrfToken);
            console.log('Form data entries:');
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value);
            }

            // إرسال البيانات
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // عرض رسالة النجاح
                    console.log('Product created successfully, showing success message...');
                    if (typeof showSuccess === 'function') {
                        console.log('Using showSuccess function');
                        showSuccess('نجح', data.message || 'تم إنشاء المنتج بنجاح');
                    } else {
                        console.log('showSuccess not available, using alert');
                        alert(data.message || 'تم إنشاء المنتج بنجاح');
                    }

                    // إعادة تفعيل الزر قبل التوجيه
                    clearTimeout(timeoutId); // Clear timeout
                    isSubmitting = false; // Reset flag
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    // إعادة توجيه إلى صفحة المنتجات بعد ثانيتين
                    setTimeout(() => {
                        const locale = document.documentElement.lang || 'ar';
                        console.log('Redirecting to products page...');
                        window.location.href = `/${locale}/dashboard/products?success=1`;
                    }, 2000);
                } else {
                    // عرض رسالة الخطأ في نفس الصفحة
                    if (typeof showError === 'function') {
                        showError('خطأ', data.message || 'حدث خطأ أثناء إنشاء المنتج');
                    } else {
                        alert(data.message || 'حدث خطأ أثناء إنشاء المنتج');
                    }
                    // إعادة تفعيل الزر
                    clearTimeout(timeoutId); // Clear timeout
                    isSubmitting = false; // Reset flag
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                console.error('Error details:', error.message);
                if (typeof showError === 'function') {
                    showError('خطأ', `حدث خطأ أثناء إرسال البيانات: ${error.message}`);
                } else {
                    alert(`حدث خطأ أثناء إرسال البيانات: ${error.message}`);
                }
                // إعادة تفعيل الزر
                clearTimeout(timeoutId); // Clear timeout
                isSubmitting = false; // Reset flag
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

            // إرسال البيانات عبر fetch
            const formData = new FormData(form);

            // إضافة CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('_token', csrfToken);

            // إضافة البيانات المطلوبة إذا لم تكن موجودة
            if (!formData.get('status')) {
                formData.append('status', 'draft');
            }
            if (!formData.get('is_active')) {
                formData.append('is_active', '0');
            }
            if (!formData.get('is_featured')) {
                formData.append('is_featured', '0');
            }

            // إرسال البيانات
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Draft Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Draft Response data:', data);
                if (data.success) {
                    // عرض رسالة النجاح
                    if (typeof showSuccess === 'function') {
                        showSuccess('نجح', data.message);
                    } else {
                        alert(data.message);
                    }
                    // إعادة توجيه إلى صفحة المنتجات بعد ثانيتين
                    setTimeout(() => {
                        const locale = document.documentElement.lang || 'ar';
                        window.location.href = `/${locale}/dashboard/products?success=1`;
                    }, 2000);
                } else {
                    // عرض رسالة الخطأ في نفس الصفحة
                    if (typeof showError === 'function') {
                        showError('خطأ', data.message);
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Draft Error:', error);
                console.error('Draft Error details:', error.message);
                if (typeof showError === 'function') {
                    showError('خطأ', `حدث خطأ أثناء حفظ المسودة: ${error.message}`);
                } else {
                    alert(`حدث خطأ أثناء حفظ المسودة: ${error.message}`);
                }
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
