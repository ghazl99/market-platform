// Simple Product Form Handler - No interference with form submission
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Product Create Page Loaded');

    const form = document.getElementById('productForm');

    if (!form) {
        console.error('❌ Form not found!');
        return;
    }

    console.log('✅ Form found:', form.action);

    // Image preview only
    const imageInput = document.getElementById('productImages');
    const imagePreview = document.getElementById('imagePreview');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" class="preview-image" alt="Product Preview" style="max-width: 200px; margin-top: 10px;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Add loading state on submit (without preventing default)
    form.addEventListener('submit', function(e) {
        console.log('🔄 Form submitting...');

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
            submitBtn.disabled = true;

            // Fallback: re-enable after 30s
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    console.warn('⚠️ Button re-enabled due to timeout');
                }
            }, 30000);
        }
    });

    console.log('✅ All handlers attached successfully');
});

