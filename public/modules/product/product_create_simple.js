// Simple Product Create JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Simple product create script loaded');
    
    const form = document.getElementById('productForm');
    const submitBtn = document.querySelector('button[type="submit"]');
    
    if (!form || !submitBtn) {
        console.log('Form or submit button not found');
        return;
    }
    
    let isSubmitting = false;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (isSubmitting) {
            console.log('Already submitting, ignoring...');
            return;
        }
        
        console.log('Form submission started');
        
        // Basic validation
        const name = form.querySelector('[name="name"]');
        const price = form.querySelector('[name="price"]');
        const category = form.querySelector('[name="category"]');
        const stock = form.querySelector('[name="stock_quantity"]');
        const description = form.querySelector('[name="description"]');
        
        if (!name || !name.value.trim()) {
            alert('يرجى إدخال اسم المنتج');
            return;
        }
        
        if (!price || !price.value.trim()) {
            alert('يرجى إدخال سعر المنتج');
            return;
        }
        
        if (!category || !category.value.trim()) {
            alert('يرجى اختيار فئة المنتج');
            return;
        }
        
        if (!stock || !stock.value.trim()) {
            alert('يرجى إدخال كمية المخزون');
            return;
        }
        
        if (!description || !description.value.trim()) {
            alert('يرجى إدخال وصف المنتج');
            return;
        }
        
        // Set loading state
        isSubmitting = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإضافة...';
        submitBtn.disabled = true;
        
        console.log('Validation passed, sending request...');
        
        // Create form data
        const formData = new FormData(form);
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }
        
        // Add default values
        if (!formData.get('status')) {
            formData.append('status', 'active');
        }
        if (!formData.get('is_active')) {
            formData.append('is_active', '1');
        }
        if (!formData.get('is_featured')) {
            formData.append('is_featured', '0');
        }
        if (!formData.get('min_quantity')) {
            formData.append('min_quantity', '1');
        }
        if (!formData.get('max_quantity')) {
            formData.append('max_quantity', '10');
        }
        
        // Send request
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response received:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            // Reset button
            isSubmitting = false;
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            if (data.success) {
                console.log('Product created successfully');
                alert('تم إنشاء المنتج بنجاح!');
                
                // Redirect after 2 seconds
                setTimeout(() => {
                    const locale = document.documentElement.lang || 'ar';
                    window.location.href = `/${locale}/dashboard/products`;
                }, 2000);
            } else {
                console.log('Product creation failed:', data.message);
                alert('خطأ: ' + (data.message || 'حدث خطأ أثناء إنشاء المنتج'));
            }
        })
        .catch(error => {
            console.error('Request failed:', error);
            
            // Reset button
            isSubmitting = false;
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            alert('خطأ في الاتصال: ' + error.message);
        });
    });
    
    // Add timeout protection
    setTimeout(() => {
        if (isSubmitting) {
            console.log('Timeout reached, resetting button');
            isSubmitting = false;
            if (submitBtn) {
                submitBtn.innerHTML = 'إضافة منتج';
                submitBtn.disabled = false;
            }
            alert('انتهت مهلة الطلب، يرجى المحاولة مرة أخرى');
        }
    }, 30000);
});
