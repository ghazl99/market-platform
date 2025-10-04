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

    // Form validation
    form.addEventListener('submit', function(e) {
        // Get form data
        const formData = new FormData(form);
        const productData = Object.fromEntries(formData.entries());

        // Basic validation
        if (!productData.name || !productData.price || !productData.category || !productData.stock_quantity || !productData.description) {
            e.preventDefault();
            if (typeof showError === 'function') {
                showError('خطأ في التحقق', 'يرجى ملء جميع الحقول المطلوبة');
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

        // Let the form submit normally - Laravel will handle the response
        // No need to prevent default or handle response here
        console.log('Form submitted normally, Laravel will handle the response');
    });

    // Real-time validation
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

    // Auto-generate SEO title from name
    const nameInput = form.querySelector('input[name="name"]');
    const seoTitleInput = form.querySelector('input[name="seo_title"]');

    if (nameInput && seoTitleInput) {
        nameInput.addEventListener('input', function() {
            if (!seoTitleInput.value) {
                seoTitleInput.value = this.value;
            }
        });
    }

    // Auto-generate SEO description from description
    const descriptionInput = form.querySelector('textarea[name="description"]');
    const seoDescriptionInput = form.querySelector('textarea[name="seo_description"]');

    if (descriptionInput && seoDescriptionInput) {
        descriptionInput.addEventListener('input', function() {
            if (!seoDescriptionInput.value) {
                const truncated = this.value.length > 150 ? this.value.substring(0, 150) + '...' : this.value;
                seoDescriptionInput.value = truncated;
            }
        });
    }
});
