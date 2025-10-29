// Mobile Menu Toggle
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
const header = document.getElementById('header');

if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('open');

        // Add overlay when sidebar opens
        if (sidebar.classList.contains('open')) {
            createOverlay();
        } else {
            removeOverlay();
        }
    });
}

// Create overlay for mobile sidebar
function createOverlay() {
    if (document.querySelector('.sidebar-overlay')) return;

    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 997;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;

    document.body.appendChild(overlay);

    setTimeout(() => {
        overlay.style.opacity = '1';
    }, 10);

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        removeOverlay();
    });
}

// Remove overlay
function removeOverlay() {
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(overlay);
        }, 300);
    }
}

// Close Sidebar when clicking outside (mobile only)
if (window.innerWidth <= 768) {
    document.addEventListener('click', (e) => {
        if (sidebar && !sidebar.contains(e.target) && !menuToggle.contains(e.target) && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            removeOverlay();
        }
    });
}

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            // Close mobile menu if open
            if (navLinks.classList.contains('active')) {
                navLinks.classList.remove('active');
            }
        }
    });
});

// Active Navigation Link on Scroll
window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    let current = '';

    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;

        if (window.pageYOffset >= sectionTop - 200) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
});

// Header Shadow on Scroll
window.addEventListener('scroll', () => {
    if (header && window.scrollY > 100) {
        header.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
    } else if (header) {
        header.style.boxShadow = '0 1px 2px 0 rgba(0, 0, 0, 0.05)';
    }
});

// Product Filter
const filterBtns = document.querySelectorAll('.filter-btn');
const productCards = document.querySelectorAll('.product-card');

if (filterBtns.length > 0) {
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));

            // Add active class to clicked button
            btn.classList.add('active');

            // Get filter value
            const filter = btn.getAttribute('data-filter');

            // Filter products
            productCards.forEach(card => {
                const category = card.getAttribute('data-category');

                if (filter === 'all' || category === filter) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
}

// Product Card Hover Effects
productCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transition = 'all 0.3s ease';
    });
});

// Cart Count Animation
const cartBtns = document.querySelectorAll('.btn-icon.fa-shopping-cart, .cart-btn');
const cartCount = document.querySelector('.cart-count');

let cartItems = 0;

cartBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();

        cartItems++;
        if (cartCount) {
            cartCount.textContent = cartItems;
            cartCount.style.animation = 'none';
            setTimeout(() => {
                cartCount.style.animation = 'pulse 0.5s ease';
            }, 10);
        }

        // Show notification (optional)
        showNotification('تم إضافة المنتج إلى العربة');
    });
});

// Notification Function
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: #10b981;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        animation: slideDown 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 2000);
}

// Hero Slider - Old indicators logic removed, replaced with new slider system

// Newsletter Form
const newsletterForm = document.querySelector('.newsletter-form');

if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const emailInput = newsletterForm.querySelector('input[type="email"]');

        if (emailInput.value) {
            showNotification('تم الاشتراك بنجاح!');
            emailInput.value = '';
        }
    });
}

// Intersection Observer for Animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for animation
const animateElements = document.querySelectorAll('.feature-card, .category-card, .product-card');

animateElements.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(el);
});

// Add pulse animation to cart count
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }

    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        to {
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        }
    }
`;
document.head.appendChild(style);

// Lazy Loading Images
if ('loading' in HTMLImageElement.prototype) {
    const images = document.querySelectorAll('img[data-src]');
    images.forEach(img => {
        img.src = img.dataset.src;
    });
} else {
    // Fallback for browsers that don't support lazy loading
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.0/lazysizes.min.js';
    document.body.appendChild(script);
}

// Add to Wishlist (heart icon)
document.querySelectorAll('.btn-icon .fa-heart').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        btn.classList.toggle('fas');
        btn.classList.toggle('far');

        if (btn.classList.contains('fas')) {
            btn.style.color = '#ec4899';
            showNotification('تم إضافة المنتج إلى المفضلة');
        } else {
            btn.style.color = '';
            showNotification('تم إزالة المنتج من المفضلة');
        }
    });
});

// Scroll to Top Button
const scrollToTopBtn = document.createElement('button');
scrollToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
scrollToTopBtn.className = 'scroll-to-top';
scrollToTopBtn.style.cssText = `
    position: fixed;
    bottom: 30px;
    left: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 1.2rem;
    cursor: pointer;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 1000;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
`;

document.body.appendChild(scrollToTopBtn);

scrollToTopBtn.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

window.addEventListener('scroll', () => {
    if (window.scrollY > 500) {
        scrollToTopBtn.style.opacity = '1';
    } else {
        scrollToTopBtn.style.opacity = '0';
    }
});

scrollToTopBtn.addEventListener('mouseenter', () => {
    scrollToTopBtn.style.transform = 'translateY(-5px) scale(1.1)';
});

scrollToTopBtn.addEventListener('mouseleave', () => {
    scrollToTopBtn.style.transform = 'translateY(0) scale(1)';
});

// Global Dark Mode Toggle Function
window.toggleDarkMode = function() {
    console.log('toggleDarkMode called');
    const darkModeBtn = document.getElementById('darkModeToggle');

    if (!darkModeBtn) {
        console.error('Dark mode button not found!');
        return;
    }

    const isDark = document.body.classList.contains('dark-mode');

    console.log('Current dark mode state:', isDark);

    if (isDark) {
        // Switch to light mode
        document.body.classList.remove('dark-mode');
        document.body.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
        darkModeBtn.innerHTML = '<i class="fas fa-moon"></i>';
        showNotification('تم تفعيل الوضع النهاري');
    } else {
        // Switch to dark mode
        document.body.classList.add('dark-mode');
        document.body.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        darkModeBtn.innerHTML = '<i class="fas fa-sun"></i>';
        showNotification('تم تفعيل الوضع الليلي');
    }
};

// Initialize Dark Mode on Load
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM Content Loaded - Initializing dark mode');

    // Set initial theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        document.body.setAttribute('data-theme', 'dark');
    }

    // Update icon
    const darkModeBtn = document.getElementById('darkModeToggle');
    if (darkModeBtn) {
        const isDark = document.body.classList.contains('dark-mode');
        darkModeBtn.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        console.log('Dark mode button initialized');
    } else {
        console.error('Dark mode button not found in DOM!');
    }
});

function logout() {
    if (confirm('هل أنت متأكد من تسجيل الخروج؟')) {
        showNotification('جاري تسجيل الخروج...', 'success');
        // Add logout logic here
    }
}

// ==================== LANGUAGE TOGGLE ====================
window.toggleLanguage = function() {
    const html = document.getElementById('html');
    const currentLang = html.getAttribute('lang');

    if (currentLang === 'ar') {
        // Switch to English
        html.setAttribute('lang', 'en');
        html.setAttribute('dir', 'ltr');
        localStorage.setItem('lang', 'en');
        document.body.classList.add('english');
        showNotification('Switched to English');
    } else {
        // Switch to Arabic
        html.setAttribute('lang', 'ar');
        html.setAttribute('dir', 'rtl');
        localStorage.setItem('lang', 'ar');
        document.body.classList.remove('english');
        showNotification('تم التبديل للعربية');
    }
};

// Initialize language
document.addEventListener('DOMContentLoaded', () => {
    const savedLang = localStorage.getItem('lang') || 'ar';
    const html = document.getElementById('html');

    if (savedLang === 'en') {
        html.setAttribute('lang', 'en');
        html.setAttribute('dir', 'ltr');
        document.body.classList.add('english');
    }
});

// ==================== HERO SLIDER ====================
let currentSlide = 0;
let slides;
let indicators;
let totalSlides;
let autoSlideInterval;

function initSlider() {
    // Get elements
    slides = document.querySelectorAll('.hero-slide');
    indicators = document.querySelectorAll('.indicator');
    totalSlides = slides.length;

    console.log('Initializing slider with', totalSlides, 'slides');

    if (totalSlides === 0) {
        console.error('No slides found!');
        return;
    }

    // Add event listeners
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');

    if (prevBtn) {
        prevBtn.addEventListener('click', () => changeSlide(-1));
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => changeSlide(1));
    }

    // Add indicator click listeners
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => goToSlide(index));
    });

    // Start with first slide
    updateSlider();

    // Start auto slide
    startAutoSlide();

    // Pause on hover
    const hero = document.querySelector('.hero');
    if (hero) {
        hero.addEventListener('mouseenter', () => {
            stopAutoSlide();
        });

        hero.addEventListener('mouseleave', () => {
            startAutoSlide();
        });
    }
}

function changeSlide(direction) {
    currentSlide += direction;

    if (currentSlide >= totalSlides) {
        currentSlide = 0;
    } else if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
    }

    console.log('Changing to slide:', currentSlide);
    updateSlider();
    resetAutoSlide();
}

function goToSlide(index) {
    currentSlide = index;
    console.log('Going to slide:', currentSlide);
    updateSlider();
    resetAutoSlide();
}

function updateSlider() {
    slides.forEach((slide, index) => {
        if (index === currentSlide) {
            slide.classList.add('active');
            slide.classList.remove('prev');
        } else if (index < currentSlide) {
            slide.classList.remove('active');
            slide.classList.add('prev');
        } else {
            slide.classList.remove('active');
            slide.classList.remove('prev');
        }
    });

    indicators.forEach((indicator, index) => {
        if (index === currentSlide) {
            indicator.classList.add('active');
        } else {
            indicator.classList.remove('active');
        }
    });
}

function startAutoSlide() {
    console.log('Starting auto slide');
    autoSlideInterval = setInterval(() => {
        currentSlide++;
        if (currentSlide >= totalSlides) {
            currentSlide = 0;
        }
        console.log('Auto changing to slide:', currentSlide);
        updateSlider();
    }, 4000); // Change slide every 4 seconds
}

function stopAutoSlide() {
    if (autoSlideInterval) {
        clearInterval(autoSlideInterval);
        autoSlideInterval = null;
    }
}

function resetAutoSlide() {
    stopAutoSlide();
    startAutoSlide();
}

// Initialize slider on page load
window.addEventListener('DOMContentLoaded', () => {
    console.log('DOM loaded, initializing slider');
    initSlider();
    initViewToggle();
});

// ==================== VIEW TOGGLE ====================
function initViewToggle() {
    const viewButtons = document.querySelectorAll('.view-btn');
    const productsGrid = document.querySelector('.products-grid');

    if (!viewButtons.length || !productsGrid) return;

    // Get saved view preference or default to grid
    const savedView = localStorage.getItem('productView') || 'grid';
    applyView(savedView);

    viewButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const view = btn.getAttribute('data-view');
            applyView(view);
            localStorage.setItem('productView', view);

            // Update active state
            viewButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
}

function applyView(view) {
    const productsGrid = document.querySelector('.products-grid');
    if (!productsGrid) return;

    if (view === 'grid') {
        productsGrid.className = 'products-grid';
    } else if (view === 'list') {
        productsGrid.className = 'products-grid list-view';
    }
}

