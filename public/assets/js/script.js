// Brand logos data
const brandLogos = [
    'https://upload.wikimedia.org/wikipedia/commons/1/1b/Apple_logo_grey.svg',
    'https://upload.wikimedia.org/wikipedia/commons/2/24/Samsung_Logo.svg',
    'https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg',
    'https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg',
    'https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg',
    'https://upload.wikimedia.org/wikipedia/commons/7/7b/Meta_Platforms_Inc._logo.svg',
    'https://upload.wikimedia.org/wikipedia/commons/b/bb/Tesla_T_symbol.svg',
    'https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg',
    'https://upload.wikimedia.org/wikipedia/commons/1/19/Spotify_logo_without_text.svg',
    'https://upload.wikimedia.org/wikipedia/commons/7/7b/Adobe_Systems_logo.svg',
    'https://upload.wikimedia.org/wikipedia/commons/7/7d/Intel_logo_%282006-2020%29.svg',
    'https://upload.wikimedia.org/wikipedia/commons/2/21/Nvidia_logo.svg'
];

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeBrandCarousel();
    initializeStatsCounter();
    initializeScrollEffects();
    initializeNavbar();
    initializeAnimations();
    initializeMobileMenu();
    initializeLanguageSwitcher();
    initializeActiveNavLink();
    initializeTouchOptimizations();
    initializeMobileGestures();
});

// Initialize brand carousel
function initializeBrandCarousel() {
    const brandsTrack = document.getElementById('brandsTrack');
    if (!brandsTrack) return;

    // Create logo elements
    const logosHTML = brandLogos.map(logo => `
        <div class="brand-logo">
            <img src="${logo}" alt="Brand Logo" loading="lazy">
        </div>
    `).join('');

    // Duplicate logos for seamless loop
    brandsTrack.innerHTML = logosHTML + logosHTML;
}

// Initialize stats counter animation
function initializeStatsCounter() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const animateCounter = (element) => {
        const target = parseFloat(element.getAttribute('data-target'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            if (target % 1 === 0) {
                element.textContent = Math.floor(current).toLocaleString();
            } else {
                element.textContent = current.toFixed(1);
            }
        }, 16);
    };

    // Intersection Observer for stats animation
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(stat => {
        statsObserver.observe(stat);
    });
}

// Initialize scroll effects
function initializeScrollEffects() {
    // Parallax effect for floating shapes
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const shapes = document.querySelectorAll('.shape');
        
        shapes.forEach((shape, index) => {
            const speed = 0.5 + (index * 0.1);
            shape.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.1}deg)`;
        });
    });

    // Fade in animation for feature cards
    const featureCards = document.querySelectorAll('.feature-card');
    const featuresObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
                featuresObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    featureCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        featuresObserver.observe(card);
    });
}

// Initialize navbar scroll effect
function initializeNavbar() {
    const navbar = document.querySelector('.navbar');
    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > 100) {
            navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            navbar.style.boxShadow = 'none';
        }

        // Hide/show navbar on scroll
        if (currentScrollY > lastScrollY && currentScrollY > 200) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
        }
        
        lastScrollY = currentScrollY;
    });
}

// Initialize general animations
function initializeAnimations() {
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Button hover effects
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Dashboard preview hover effect
    const dashboardPreview = document.querySelector('.dashboard-preview');
    if (dashboardPreview) {
        dashboardPreview.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02) rotateY(5deg)';
        });
        
        dashboardPreview.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotateY(0deg)';
        });
    }

    // Brand logos hover effect
    document.addEventListener('mouseover', function(e) {
        if (e.target.closest('.brand-logo')) {
            e.target.closest('.brand-logo').style.transform = 'scale(1.1) rotate(5deg)';
        }
    });

    document.addEventListener('mouseout', function(e) {
        if (e.target.closest('.brand-logo')) {
            e.target.closest('.brand-logo').style.transform = 'scale(1) rotate(0deg)';
        }
    });
}

// Utility function for smooth animations
function easeInOutCubic(t) {
    return t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
}

// Add loading animation
window.addEventListener('load', function() {
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.5s ease';
    
    setTimeout(() => {
        document.body.style.opacity = '1';
    }, 100);
});

// Add click effects to buttons
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn')) {
        const button = e.target.closest('.btn');
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        `;
        
        button.style.position = 'relative';
        button.style.overflow = 'hidden';
        button.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
});

// Initialize mobile menu
function initializeMobileMenu() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (!mobileMenuToggle || !mobileMenu) return;
    
    mobileMenuToggle.addEventListener('click', function() {
        this.classList.toggle('active');
        mobileMenu.classList.toggle('active');
        document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
    });
    
    // Close mobile menu when clicking on links
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenuToggle.classList.remove('active');
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
            mobileMenuToggle.classList.remove('active');
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

// Initialize language switcher
function initializeLanguageSwitcher() {
    const langButtons = document.querySelectorAll('.lang-btn');
    
    langButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            langButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Here you can add language switching logic
            console.log('Language switched to:', this.textContent);
        });
    });
}

// Initialize active nav link
function initializeActiveNavLink() {
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    
    // Function to update active link
    function updateActiveLink() {
        const sections = document.querySelectorAll('section[id]');
        const scrollPos = window.scrollY + 100;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${sectionId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }
    
    // Update on scroll
    window.addEventListener('scroll', updateActiveLink);
    
    // Update on page load
    updateActiveLink();
}

// Initialize touch optimizations for mobile
function initializeTouchOptimizations() {
    // Prevent zoom on double tap for better mobile experience
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function (event) {
        const now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    // Improve touch scrolling performance
    document.addEventListener('touchstart', function() {}, { passive: true });
    document.addEventListener('touchmove', function() {}, { passive: true });

    // Add touch feedback for interactive elements
    const touchElements = document.querySelectorAll('.btn, .nav-link, .mobile-nav-link, .feature-card, .store-type-card, .partner-item, .step-item, .pricing-card');
    
    touchElements.forEach(element => {
        element.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
            this.style.transition = 'transform 0.1s ease';
        });
        
        element.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
        
        element.addEventListener('touchcancel', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Optimize scroll performance on mobile
    let ticking = false;
    function updateScrollEffects() {
        // Your scroll effects here
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateScrollEffects);
            ticking = true;
        }
    });
}

// Initialize mobile gestures
function initializeMobileGestures() {
    // Swipe gestures for mobile menu
    let startX = 0;
    let startY = 0;
    let endX = 0;
    let endY = 0;

    document.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
    });

    document.addEventListener('touchend', function(e) {
        endX = e.changedTouches[0].clientX;
        endY = e.changedTouches[0].clientY;
        handleSwipe();
    });

    function handleSwipe() {
        const diffX = startX - endX;
        const diffY = startY - endY;
        const mobileMenu = document.querySelector('.mobile-menu');
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');

        // Check if it's a horizontal swipe
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
            if (diffX > 0) {
                // Swipe left - close mobile menu if open
                if (mobileMenu && mobileMenu.classList.contains('active')) {
                    mobileMenuToggle.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                }
            } else {
                // Swipe right - could open mobile menu (optional)
                // This is commented out to prevent accidental opening
                // if (mobileMenu && !mobileMenu.classList.contains('active')) {
                //     mobileMenuToggle.classList.add('active');
                //     mobileMenu.classList.add('active');
                //     document.body.style.overflow = 'hidden';
                // }
            }
        }
    }

    // Add pull-to-refresh effect (visual only)
    let pullStartY = 0;
    let pullCurrentY = 0;
    let isPulling = false;

    document.addEventListener('touchstart', function(e) {
        if (window.scrollY === 0) {
            pullStartY = e.touches[0].clientY;
            isPulling = true;
        }
    });

    document.addEventListener('touchmove', function(e) {
        if (isPulling && window.scrollY === 0) {
            pullCurrentY = e.touches[0].clientY;
            const pullDistance = pullCurrentY - pullStartY;
            
            if (pullDistance > 0 && pullDistance < 100) {
                // Add visual feedback for pull-to-refresh
                document.body.style.transform = `translateY(${pullDistance * 0.3}px)`;
            }
        }
    });

    document.addEventListener('touchend', function() {
        if (isPulling) {
            document.body.style.transform = 'translateY(0)';
            document.body.style.transition = 'transform 0.3s ease';
            setTimeout(() => {
                document.body.style.transition = '';
            }, 300);
            isPulling = false;
        }
    });
}

// Add ripple animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    /* Mobile-specific optimizations */
    @media (max-width: 768px) {
        * {
            -webkit-tap-highlight-color: transparent;
        }
        
        .btn, .nav-link, .mobile-nav-link, .feature-card, .store-type-card, .partner-item, .step-item, .pricing-card {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Improve scrolling on iOS */
        .hero, .store-types, .trusted-partners, .how-it-works, .pricing-preview, .features, .footer {
            -webkit-overflow-scrolling: touch;
        }
        
        /* Prevent horizontal scroll */
        body {
            overflow-x: hidden;
        }
        
        /* Improve text rendering on mobile */
        body, .hero-title, .hero-description, .section-title, .section-subtitle {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    }
`;
document.head.appendChild(style);
