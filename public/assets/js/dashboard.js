// Dashboard JavaScript Functions

// Sidebar Management
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const isMobile = window.innerWidth <= 1024;

    if (isMobile) {
        // Mobile behavior: toggle open/close with overlay
        sidebar.classList.toggle('open');
        let overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar.classList.contains('open')) {
            // Create overlay if it doesn't exist
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'sidebar-overlay';
                overlay.className = 'sidebar-overlay';
                overlay.addEventListener('click', closeMobileSidebar);
                document.body.appendChild(overlay);
                // Trigger reflow to ensure transition works
                void overlay.offsetWidth;
            }
            // Add active class for animation
            setTimeout(() => {
                overlay.classList.add('active');
            }, 10);
            document.body.style.overflow = 'hidden'; // Prevent body scroll
        } else {
            // Remove active class first, then remove element after transition
            if (overlay) {
                overlay.classList.remove('active');
                setTimeout(() => {
                    if (overlay && overlay.parentNode) {
                        overlay.remove();
                    }
                }, 300); // Match transition duration
            }
            document.body.style.overflow = '';
        }
    } else {
        // Desktop behavior: collapse/expand
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');

        // Save state to localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }
}

// Mobile sidebar toggle (alias for consistency)
function toggleMobileSidebar() {
    toggleSidebar();
}

// Close mobile sidebar
function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    sidebar.classList.remove('open');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => {
            if (overlay && overlay.parentNode) {
                overlay.remove();
            }
        }, 300); // Match transition duration
    }
    document.body.style.overflow = '';
}

// Close sidebar when clicking on a nav link (mobile only)
function setupMobileNavLinks() {
    const navLinks = document.querySelectorAll('.nav-item');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Only close on mobile
            if (window.innerWidth <= 1024) {
                setTimeout(() => {
                    closeMobileSidebar();
                }, 100); // Small delay for better UX
            }
        });
    });
}

// Handle window resize
function handleResize() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const isMobile = window.innerWidth <= 1024;

    if (!isMobile) {
        // On desktop, remove mobile classes
        sidebar.classList.remove('open');
        if (overlay) {
            overlay.remove();
        }
        document.body.style.overflow = '';
    } else {
        // On mobile, remove desktop collapse classes
        sidebar.classList.remove('collapsed');
        const mainContent = document.getElementById('mainContent');
        if (mainContent) {
            mainContent.classList.remove('expanded');
        }
    }
}

// Initialize sidebar state
function initializeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const isMobile = window.innerWidth <= 1024;

    if (!isMobile) {
        // Only restore collapsed state on desktop
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
        }
    } else {
        // On mobile, ensure sidebar is closed initially
        sidebar.classList.remove('open');
        sidebar.classList.remove('collapsed');
        if (mainContent) {
            mainContent.classList.remove('expanded');
        }
    }

    // Setup mobile nav links
    setupMobileNavLinks();
}

// Chart buttons functionality
function initializeChartButtons() {
    document.querySelectorAll('.chart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Here you would typically update the chart data
            updateChartData(this.textContent.trim());
        });
    });
}

// Update chart data based on selected period
function updateChartData(period) {
    console.log(`Updating chart for period: ${period}`);
    // This would typically make an API call to get new data
    // For now, we'll just log the action
}

// Statistics animation
function animateStats() {
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Ripple effect for interactive elements
function addRippleEffect() {
    document.querySelectorAll('.nav-item, .chart-btn, .notifications, .user-menu').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

// Search functionality
function initializeSearch() {
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            console.log(`Searching for: ${query}`);
            // Implement search functionality here
        });
    }
}

// Notifications management
function initializeNotifications() {
    const notificationBtn = document.querySelector('.notifications');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function() {
            // Toggle notifications panel
            console.log('Notifications clicked');
            // Implement notifications panel here
        });
    }
}

// User menu functionality
function initializeUserMenu() {
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        userMenu.addEventListener('click', function() {
            // Toggle user dropdown
            console.log('User menu clicked');
            // Implement user dropdown here
        });
    }
}

// Navigation management
// function initializeNavigation() {
//     const navItems = document.querySelectorAll('.nav-item');
//     navItems.forEach(item => {
//         item.addEventListener('click', function(e) {
//             e.preventDefault();

//             // Remove active class from all items
//             navItems.forEach(nav => nav.classList.remove('active'));

//             // Add active class to clicked item
//             this.classList.add('active');

//             // Get the page name from the item
//             const pageName = this.querySelector('.nav-item-text').textContent.trim();
//             console.log(`Navigating to: ${pageName}`);

//             // Update breadcrumb
//             updateBreadcrumb(pageName);

//             // Load page content
//             loadPageContent(pageName);
//         });
//     });
// }

// Update breadcrumb
function updateBreadcrumb(pageName) {
    const breadcrumb = document.querySelector('.breadcrumb');
    if (breadcrumb) {
        breadcrumb.innerHTML = `
            <i class="fas fa-home"></i>
            <span>الرئيسية</span>
            <i class="fas fa-chevron-left"></i>
            <span>${pageName}</span>
        `;
    }
}

// Load page content (placeholder)
function loadPageContent(pageName) {
    const content = document.querySelector('.dashboard-content');
    if (content) {
        // This would typically load different content based on the page
        console.log(`Loading content for: ${pageName}`);

        // For demo purposes, update the page title
        const pageTitle = document.querySelector('.page-title');
        if (pageTitle) {
            pageTitle.textContent = pageName;
        }
    }
}

// Real-time data updates (simulation)
function simulateRealTimeUpdates() {
    setInterval(() => {
        // Simulate real-time data updates
        updateStatCards();
        updateActivityFeed();
    }, 30000); // Update every 30 seconds
}

// Update stat cards with new data
function updateStatCards() {
    const statValues = document.querySelectorAll('.stat-value');
    statValues.forEach(value => {
        // Add a subtle animation to indicate data update
        value.style.transform = 'scale(1.05)';
        setTimeout(() => {
            value.style.transform = 'scale(1)';
        }, 200);
    });
}

// Update activity feed
function updateActivityFeed() {
    const activityList = document.querySelector('.activity-list');
    if (activityList) {
        // Add a subtle pulse animation to indicate new activity
        activityList.style.animation = 'pulse 0.5s ease-in-out';
        setTimeout(() => {
            activityList.style.animation = '';
        }, 500);
    }
}

// Theme management
function initializeTheme() {
    // Check for saved theme preference (already applied in head script, but sync here)
    const savedTheme = localStorage.getItem('dashboard-theme') || 
                      localStorage.getItem('theme') || 
                      'light';
    
    // Ensure theme is applied (redundant but ensures sync)
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
}

// Toggle theme
function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    document.documentElement.setAttribute('data-theme', newTheme);
    
    // Save to both keys for compatibility
    localStorage.setItem('dashboard-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    // Update body class for backward compatibility
    if (newTheme === 'dark') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
    
    updateThemeIcon(newTheme);

    // Add transition effect
    document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
    setTimeout(() => {
        document.body.style.transition = '';
    }, 300);
    
    // Trigger custom event for other scripts
    window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: newTheme } }));
}

// Update theme icon
function updateThemeIcon(theme) {
    const themeIcon = document.getElementById('theme-icon');
    if (themeIcon) {
        if (theme === 'dark') {
            themeIcon.className = 'fas fa-sun';
        } else {
            themeIcon.className = 'fas fa-moon';
        }
    }
}

// Listen for system theme changes
function initializeSystemThemeListener() {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    mediaQuery.addEventListener('change', (e) => {
        // Only apply system theme if no user preference is saved
        if (!localStorage.getItem('dashboard-theme')) {
            const newTheme = e.matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', newTheme);
            updateThemeIcon(newTheme);
        }
    });
}

// Keyboard shortcuts
function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + B to toggle sidebar
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            toggleSidebar();
        }

        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('.search-box input');
            if (searchInput) {
                searchInput.focus();
            }
        }

        // Ctrl/Cmd + Shift + T to toggle theme
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
            e.preventDefault();
            toggleTheme();
        }

        // Escape to close mobile sidebar
        if (e.key === 'Escape') {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && sidebar.classList.contains('open')) {
                closeMobileSidebar();
            }
        }
    });
}

// Performance monitoring
function initializePerformanceMonitoring() {
    // Monitor page load time
    window.addEventListener('load', function() {
        const loadTime = performance.now();
        console.log(`Page loaded in ${loadTime.toFixed(2)}ms`);
    });

    // Monitor scroll performance
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            // Throttled scroll handling
        }, 100);
    });
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard initializing...');

    // Initialize all components
    initializeSidebar();
    initializeChartButtons();
    initializeSearch();
    initializeNotifications();
    initializeUserMenu();
    // initializeNavigation();
    initializeTheme();
    initializeSystemThemeListener();
    initializeKeyboardShortcuts();
    initializePerformanceMonitoring();

    // Add visual effects
    animateStats();
    addRippleEffect();

    // Start real-time updates
    simulateRealTimeUpdates();

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(handleResize, 250);
    });

    console.log('Dashboard initialized successfully!');
});

// Export functions for global access
window.toggleSidebar = toggleSidebar;
window.toggleMobileSidebar = toggleMobileSidebar;
window.closeMobileSidebar = closeMobileSidebar;
window.toggleTheme = toggleTheme;
