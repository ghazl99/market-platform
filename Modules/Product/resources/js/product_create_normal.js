// Product create page specific JavaScript - Normal form submission like Category
document.addEventListener('DOMContentLoaded', function() {
    console.log('Product create script loaded - Normal form submission');

    const form = document.getElementById('productForm');
    const imageInput = document.getElementById('productImages');
    const imagePreview = document.getElementById('imagePreview');

    // Image upload preview
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" class="preview-image" alt="Product Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form submission handler
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');

            // Get form data for validation only (logging)
            const formData = new FormData(form);
            const productData = Object.fromEntries(formData.entries());

            console.log('Form data:', productData);

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
                submitBtn.disabled = true;

                // Re-enable button after 30 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 30000);
            }

            // Let the form submit normally - Laravel will handle validation
            console.log('Allowing form to submit normally');
        });
    } else {
        console.error('Form not found!');
    }

    // Real-time validation
    if (form) {
        const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.style.borderColor = '#ef4444';
                } else {
                    this.style.borderColor = '';
                }
            });
        });
    }

    // Auto-generate SEO title from name
    const nameInput = form ? form.querySelector('input[name="name"]') : null;
    const seoTitleInput = form ? form.querySelector('input[name="seo_title"]') : null;

    if (nameInput && seoTitleInput) {
        nameInput.addEventListener('input', function() {
            if (!seoTitleInput.value) {
                seoTitleInput.value = this.value;
            }
        });
    }

    // Auto-generate SEO description from description
    const descriptionInput = form ? form.querySelector('textarea[name="description"]') : null;
    const seoDescriptionInput = form ? form.querySelector('textarea[name="seo_description"]') : null;

    if (descriptionInput && seoDescriptionInput) {
        descriptionInput.addEventListener('input', function() {
            if (!seoDescriptionInput.value) {
                const truncated = this.value.length > 150 ? this.value.substring(0, 150) + '...' : this.value;
                seoDescriptionInput.value = truncated;
            }
        });
    }
});
