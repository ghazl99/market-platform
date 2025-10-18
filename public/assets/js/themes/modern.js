// DOM Elements
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
const darkModeToggle = document.getElementById('darkModeToggle');

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});

// Initialize App
function initializeApp() {
    // Initialize sidebar
    initializeSidebar();

    // Initialize slideshow
    initializeSlideshow();

    // Initialize dark mode
    initializeDarkMode();

    // Initialize navigation
    initializeNavigation();

    // Initialize header
    initializeHeader();

    // Initialize search
    initializeSearch();

    // Initialize notifications
    initializeNotifications();
}

// Sidebar Functions
function initializeSidebar() {
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking outside
        document.addEventListener('click', function (event) {
            if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                closeSidebar();
            }
        });
    }
}

function toggleSidebar() {
    sidebar.classList.toggle('open');
}

function closeSidebar() {
    sidebar.classList.remove('open');
}

// Slideshow Functions
function initializeSlideshow() {
    let slideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    function showSlide(n) {
        if (n > slides.length) { slideIndex = 1; }
        if (n < 1) { slideIndex = slides.length; }

        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        slides[slideIndex - 1].classList.add('active');
        dots[slideIndex - 1].classList.add('active');
    }

    function nextSlide() {
        slideIndex++;
        showSlide(slideIndex);
    }

    // Auto slide every 3 seconds
    setInterval(nextSlide, 3000);
}

function currentSlide(n) {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));

    slides[n - 1].classList.add('active');
    dots[n - 1].classList.add('active');
}

// Dark Mode Functions
function initializeDarkMode() {
    if (darkModeToggle) {
        // Load saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            darkModeToggle.checked = true;
        }

        // Toggle dark mode
        darkModeToggle.addEventListener('change', function () {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    }
}

// Navigation Functions
function initializeNavigation() {
    const navItems = document.querySelectorAll('.nav-item');

    navItems.forEach(item => {
        item.addEventListener('click', function (e) {
            // Remove active class from all items
            navItems.forEach(nav => nav.classList.remove('active'));

            // Add active class to clicked item
            this.classList.add('active');

            // Close sidebar only on mobile devices
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    });
}

// Logout Function
function logout() {
    if (confirm('هل أنت متأكد من تسجيل الخروج؟')) {
        // Add logout logic here
        console.log('Logging out...');
        // window.location.href = '/logout';
    }
}

// Header Functions
function initializeHeader() {
    // Initialize header progress bar
    initializeHeaderProgress();

    // Initialize ripple effects
    initializeRippleEffects();

    // Initialize user profile dropdown
    initializeUserProfile();

    // Initialize wallet functionality
    initializeWallet();
}

function initializeHeaderProgress() {
    const progressBar = document.querySelector('.header-progress');

    if (progressBar) {
        // Simulate loading progress
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
            }
            progressBar.style.width = progress + '%';
        }, 100);

        // Reset progress on page load
        window.addEventListener('load', () => {
            progressBar.style.width = '100%';
            setTimeout(() => {
                progressBar.style.width = '0%';
            }, 1000);
        });
    }
}

function initializeRippleEffects() {
    const actionBtns = document.querySelectorAll('.action-btn');

    actionBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            const ripple = this.querySelector('.btn-ripple');
            if (ripple) {
                ripple.style.width = '0px';
                ripple.style.height = '0px';

                setTimeout(() => {
                    ripple.style.width = '200px';
                    ripple.style.height = '200px';
                }, 10);
            }
        });
    });
}

function initializeUserProfile() {
    const userProfile = document.querySelector('.user-profile');
    const userDropdown = document.querySelector('.user-dropdown');

    if (userProfile && userDropdown) {
        userProfile.addEventListener('click', function (e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!userProfile.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }
}

function initializeWallet() {
    const walletBtn = document.querySelector('.wallet-btn');

    if (walletBtn) {
        walletBtn.addEventListener('click', function () {
            // Add wallet click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);

            // Redirect to wallet page
            window.location.href = 'wallet.html';
        });
    }
}

function initializeNotifications() {
    const notificationBtn = document.querySelector('.notification-btn');
    const notificationsDropdown = document.querySelector('.notifications-dropdown');
    const markAllReadBtn = document.querySelector('.mark-all-read');

    if (notificationBtn && notificationsDropdown) {
        notificationBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            notificationsDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!notificationBtn.contains(e.target) && !notificationsDropdown.contains(e.target)) {
                notificationsDropdown.classList.remove('show');
            }
        });
    }

    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function () {
            const unreadItems = document.querySelectorAll('.notification-item.unread');
            unreadItems.forEach(item => {
                item.classList.remove('unread');
            });

            // Update badge count
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                badge.textContent = '0';
                badge.style.display = 'none';
            }

            showNotification('تم تعيين جميع الإشعارات كمقروءة', 'success');
        });
    }
}

function toggleUserMenu() {
    const dropdown = document.querySelector('.user-dropdown');
    const notifications = document.querySelector('.notifications-dropdown');

    // Close notifications if open
    if (notifications) {
        notifications.classList.remove('show');
    }

    // Toggle user dropdown
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

function toggleNotifications() {
    const dropdown = document.querySelector('.notifications-dropdown');
    const user = document.querySelector('.user-dropdown');

    // Close user dropdown if open
    if (user) {
        user.classList.remove('show');
    }

    // Toggle notifications dropdown
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

function openWallet() {
    // Add animation effect
    const walletBtn = document.querySelector('.wallet-btn');
    if (walletBtn) {
        walletBtn.style.transform = 'scale(0.95)';
        setTimeout(() => {
            walletBtn.style.transform = '';
        }, 150);
    }

    // Redirect to wallet page or open wallet modal
    window.location.href = 'wallet.html';
}

// Close dropdowns when clicking outside
document.addEventListener('click', function (event) {
    const userDropdown = document.getElementById('userDropdown');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    const userProfile = document.querySelector('.user-profile');
    const notificationBtn = document.querySelector('.notification-btn');

    if (!userProfile.contains(event.target)) {
        userDropdown.classList.remove('show');
    }

    if (!notificationBtn.contains(event.target)) {
        notificationsDropdown.classList.remove('show');
    }
});

// Search functionality
function initializeSearch() {
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    const suggestionTags = document.querySelectorAll('.suggestion-tag');

    if (searchBtn) {
        searchBtn.addEventListener('click', function () {
            const query = searchInput.value.trim();
            if (query) {
                performSearch(query);
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    performSearch(query);
                }
            }
        });

        // Add focus/blur effects
        searchInput.addEventListener('focus', function () {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.boxShadow = '0 0 20px rgba(255,255,255,0.3)';
        });

        searchInput.addEventListener('blur', function () {
            this.parentElement.style.transform = '';
            this.parentElement.style.boxShadow = '';
        });

        // Add typing animation
        searchInput.addEventListener('input', function () {
            if (this.value.length > 0) {
                this.style.color = '#fff';
            } else {
                this.style.color = 'rgba(255,255,255,0.7)';
            }
        });
    }

    // Add click functionality to suggestion tags
    suggestionTags.forEach(tag => {
        tag.addEventListener('click', function () {
            searchInput.value = this.textContent;
            performSearch(this.textContent);
        });
    });
}

function performSearch(query) {
    // Add search animation
    const searchBtn = document.querySelector('.search-btn');
    if (searchBtn) {
        searchBtn.style.transform = 'scale(0.95)';
        setTimeout(() => {
            searchBtn.style.transform = '';
        }, 150);
    }

    // Show loading state
    showNotification('جاري البحث...', 'info');

    // Simulate search delay
    setTimeout(() => {
        console.log('Searching for:', query);
        showNotification(`تم العثور على نتائج لـ "${query}"`, 'success');

        // You can implement actual search functionality here
        // For example: redirect to search results page
        // window.location.href = `search.html?q=${encodeURIComponent(query)}`;
    }, 1000);
}

// Utility Functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    initializeSlideshow();
    initializeNavigation();
    initializeTheme();
    initializeSidebar();
    initializeAnimations();
    initializePerformance();
    initializePWA();
    initializeOffers();
    initializeSearch();
});

// Export functions for global use
window.currentSlide = currentSlide;
window.logout = logout;
window.showNotification = showNotification;
window.toggleUserMenu = toggleUserMenu;
window.toggleNotifications = toggleNotifications;
window.openWallet = openWallet;
