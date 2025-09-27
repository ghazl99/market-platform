// Auth Script for Login and Register Pages

document.addEventListener('DOMContentLoaded', function() {
    initializeAuthForms();
    initializePasswordToggles();
    initializePasswordStrength();
    initializeSocialLogin();
    initializeFormValidation();
});

// Initialize Auth Forms
function initializeAuthForms() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }
}

// Handle Login Form
async function handleLogin(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const loginData = {
        email: formData.get('email'),
        password: formData.get('password'),
        rememberMe: formData.get('rememberMe') === 'on'
    };
    
    // Show loading
    showLoading('جاري تسجيل الدخول...');
    
    try {
        // Simulate API call
        await simulateApiCall(2000);
        
        // Store login data
        localStorage.setItem('user', JSON.stringify({
            email: loginData.email,
            name: 'مستخدم متجري',
            loginTime: new Date().toISOString()
        }));
        
        // Show success message
        showNotification('تم تسجيل الدخول بنجاح!', 'success');
        
        // Redirect to dashboard or home
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1500);
        
    } catch (error) {
        showNotification('خطأ في تسجيل الدخول. تحقق من البيانات المدخلة.', 'error');
    } finally {
        hideLoading();
    }
}

// Handle Register Form
async function handleRegister(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const registerData = {
        firstName: formData.get('firstName'),
        lastName: formData.get('lastName'),
        email: formData.get('email'),
        phone: formData.get('phone'),
        password: formData.get('password'),
        confirmPassword: formData.get('confirmPassword'),
        storeType: formData.get('storeType'),
        agreeTerms: formData.get('agreeTerms') === 'on'
    };
    
    // Validate passwords match
    if (registerData.password !== registerData.confirmPassword) {
        showNotification('كلمات المرور غير متطابقة', 'error');
        return;
    }
    
    // Validate password strength
    if (!isPasswordStrong(registerData.password)) {
        showNotification('كلمة المرور ضعيفة. يرجى اختيار كلمة مرور أقوى.', 'error');
        return;
    }
    
    // Show loading
    showLoading('جاري إنشاء الحساب...');
    
    try {
        // Simulate API call
        await simulateApiCall(3000);
        
        // Store user data
        localStorage.setItem('user', JSON.stringify({
            firstName: registerData.firstName,
            lastName: registerData.lastName,
            email: registerData.email,
            phone: registerData.phone,
            storeType: registerData.storeType,
            registerTime: new Date().toISOString()
        }));
        
        // Show success message
        showNotification('تم إنشاء الحساب بنجاح! مرحباً بك في متجري', 'success');
        
        // Redirect to dashboard or home
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 2000);
        
    } catch (error) {
        showNotification('خطأ في إنشاء الحساب. يرجى المحاولة مرة أخرى.', 'error');
    } finally {
        hideLoading();
    }
}

// Initialize Password Toggles
function initializePasswordToggles() {
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
}

// Initialize Password Strength Checker
function initializePasswordStrength() {
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    
    if (passwordInput && strengthFill && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            
            // Update strength bar
            strengthFill.className = 'strength-fill ' + strength.level;
            strengthText.className = 'strength-text ' + strength.level;
            strengthText.textContent = strength.text;
        });
    }
}

// Check Password Strength
function checkPasswordStrength(password) {
    let score = 0;
    let feedback = [];
    
    // Length check
    if (password.length >= 8) score += 1;
    else feedback.push('8 أحرف على الأقل');
    
    // Lowercase check
    if (/[a-z]/.test(password)) score += 1;
    else feedback.push('حرف صغير');
    
    // Uppercase check
    if (/[A-Z]/.test(password)) score += 1;
    else feedback.push('حرف كبير');
    
    // Number check
    if (/\d/.test(password)) score += 1;
    else feedback.push('رقم');
    
    // Special character check
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score += 1;
    else feedback.push('رمز خاص');
    
    if (score < 2) {
        return { level: 'weak', text: 'كلمة مرور ضعيفة' };
    } else if (score < 4) {
        return { level: 'fair', text: 'كلمة مرور مقبولة' };
    } else if (score < 5) {
        return { level: 'good', text: 'كلمة مرور جيدة' };
    } else {
        return { level: 'strong', text: 'كلمة مرور قوية' };
    }
}

// Check if password is strong enough
function isPasswordStrong(password) {
    const strength = checkPasswordStrength(password);
    return strength.level === 'good' || strength.level === 'strong';
}

// Initialize Social Login
function initializeSocialLogin() {
    const googleLogin = document.getElementById('googleLogin');
    const googleRegister = document.getElementById('googleRegister');
    
    if (googleLogin) {
        googleLogin.addEventListener('click', handleGoogleLogin);
    }
    
    if (googleRegister) {
        googleRegister.addEventListener('click', handleGoogleRegister);
    }
}

// Handle Google Login
async function handleGoogleLogin() {
    showLoading('جاري تسجيل الدخول بواسطة جوجل...');
    
    try {
        // Simulate Google OAuth
        await simulateApiCall(2000);
        
        // Store user data
        localStorage.setItem('user', JSON.stringify({
            email: 'user@gmail.com',
            name: 'مستخدم جوجل',
            provider: 'google',
            loginTime: new Date().toISOString()
        }));
        
        showNotification('تم تسجيل الدخول بواسطة جوجل بنجاح!', 'success');
        
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1500);
        
    } catch (error) {
        showNotification('خطأ في تسجيل الدخول بواسطة جوجل', 'error');
    } finally {
        hideLoading();
    }
}

// Handle Google Register
async function handleGoogleRegister() {
    showLoading('جاري إنشاء الحساب بواسطة جوجل...');
    
    try {
        // Simulate Google OAuth
        await simulateApiCall(2000);
        
        // Store user data
        localStorage.setItem('user', JSON.stringify({
            email: 'user@gmail.com',
            firstName: 'مستخدم',
            lastName: 'جوجل',
            provider: 'google',
            registerTime: new Date().toISOString()
        }));
        
        showNotification('تم إنشاء الحساب بواسطة جوجل بنجاح!', 'success');
        
        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1500);
        
    } catch (error) {
        showNotification('خطأ في إنشاء الحساب بواسطة جوجل', 'error');
    } finally {
        hideLoading();
    }
}

// Initialize Form Validation
function initializeFormValidation() {
    const forms = document.querySelectorAll('.auth-form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    validateField(this);
                }
            });
        });
    });
}

// Validate Individual Field
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.getAttribute('name');
    let isValid = true;
    let errorMessage = '';
    
    // Remove existing error styling
    field.classList.remove('error');
    removeErrorMessage(field);
    
    // Required field check
    if (!value) {
        isValid = false;
        errorMessage = 'هذا الحقل مطلوب';
    }
    
    // Email validation
    if (fieldName === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            errorMessage = 'يرجى إدخال بريد إلكتروني صحيح';
        }
    }
    
    // Phone validation
    if (fieldName === 'phone' && value) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        if (!phoneRegex.test(value)) {
            isValid = false;
            errorMessage = 'يرجى إدخال رقم هاتف صحيح';
        }
    }
    
    // Password confirmation
    if (fieldName === 'confirmPassword' && value) {
        const password = document.getElementById('password').value;
        if (value !== password) {
            isValid = false;
            errorMessage = 'كلمات المرور غير متطابقة';
        }
    }
    
    if (!isValid) {
        field.classList.add('error');
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

// Show Field Error
function showFieldError(field, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.color = '#ef4444';
    errorDiv.style.fontSize = '0.8rem';
    errorDiv.style.marginTop = '0.25rem';
    
    field.parentElement.appendChild(errorDiv);
}

// Remove Field Error
function removeErrorMessage(field) {
    const existingError = field.parentElement.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Show Loading Overlay
function showLoading(message = 'جاري التحميل...') {
    const overlay = document.getElementById('loadingOverlay');
    const messageElement = overlay.querySelector('p');
    
    if (messageElement) {
        messageElement.textContent = message;
    }
    
    overlay.classList.add('show');
}

// Hide Loading Overlay
function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    overlay.classList.remove('show');
}

// Show Notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 400px;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentElement) {
                notification.parentElement.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Simulate API Call
function simulateApiCall(delay = 1000) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            // Simulate 90% success rate
            if (Math.random() > 0.1) {
                resolve();
            } else {
                reject(new Error('API Error'));
            }
        }, delay);
    });
}

// Check if user is logged in
function checkAuthStatus() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

// Logout function
function logout() {
    localStorage.removeItem('user');
    window.location.href = 'login.html';
}

// Add error styles to CSS
const errorStyles = `
    .form-input.error,
    .form-select.error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .notification-content i {
        font-size: 1.2rem;
    }
`;

// Inject error styles
const styleSheet = document.createElement('style');
styleSheet.textContent = errorStyles;
document.head.appendChild(styleSheet);

