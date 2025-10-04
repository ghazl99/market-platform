// Category create page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('categoryForm');
    const imageInput = document.getElementById('categoryImage');
    const imagePreview = document.getElementById('imagePreview');
    const iconPicker = document.getElementById('iconPicker');
    const selectedIconInput = document.getElementById('selectedIcon');

    // Icon picker
    iconPicker.addEventListener('click', function(e) {
        if (e.target.closest('.icon-option')) {
            const iconOption = e.target.closest('.icon-option');
            const iconClass = iconOption.dataset.icon;

            // Remove selected class from all options
            iconPicker.querySelectorAll('.icon-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Add selected class to clicked option
            iconOption.classList.add('selected');

            // Set hidden input value
            selectedIconInput.value = iconClass;
        }
    });

    // Image upload preview
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" class="preview-image" alt="Category Preview">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        // Get form data
        const formData = new FormData(form);
        const categoryData = Object.fromEntries(formData.entries());

        // Basic validation
        if (!categoryData.name || !categoryData.description || !categoryData.icon) {
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

    nameInput.addEventListener('input', function() {
        if (!seoTitleInput.value) {
            seoTitleInput.value = this.value;
        }
    });

    // Auto-generate SEO description from description
    const descriptionInput = form.querySelector('textarea[name="description"]');
    const seoDescriptionInput = form.querySelector('textarea[name="seo_description"]');

    descriptionInput.addEventListener('input', function() {
        if (!seoDescriptionInput.value) {
            const truncated = this.value.length > 150 ? this.value.substring(0, 150) + '...' : this.value;
            seoDescriptionInput.value = truncated;
        }
    });
});
