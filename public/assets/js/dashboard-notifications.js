/**
 * Dashboard Notifications JavaScript
 * Handles notification interactions and real-time updates
 */

class DashboardNotifications {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupAutoRefresh();
        this.setupNotificationActions();
    }

    setupEventListeners() {
        // Individual notification actions - disabled to avoid conflict with modal system
        // The delete functionality is now handled by the modal system in the page
        console.log('Dashboard notifications initialized - modal system active');
    }

    setupAutoRefresh() {
        // Auto refresh notifications every 30 seconds
        setInterval(() => {
            this.refreshNotificationCount();
        }, 30000);
    }

    setupNotificationActions() {
        try {
            // Add hover effects to notification items
            document.querySelectorAll('.notification-item').forEach(item => {
                try {
                    item.addEventListener('mouseenter', () => {
                        item.style.transform = 'translateX(4px)';
                    });

                    item.addEventListener('mouseleave', () => {
                        item.style.transform = 'translateX(0)';
                    });
                } catch (e) {
                    console.warn('Error setting up notification item actions:', e);
                }
            });
        } catch (error) {
            console.warn('Error in setupNotificationActions:', error);
        }
    }


    // Delete notification method removed - now handled by modal system

    async refreshNotificationCount() {
        try {
            const response = await fetch('/dashboard/notifications/count', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateNotificationBadges(data.count);
            }
        } catch (error) {
            // Silently ignore fetch errors (network issues, etc.)
            // Only log if it's not a network error
            if (error.name !== 'TypeError' && error.name !== 'NetworkError') {
                console.warn('Error refreshing notification count:', error);
            }
        }
    }

    updateNotificationBadges(count) {
        // Update top bar badge
        const topBarBadge = document.querySelector('.notification-badge');
        if (count > 0) {
            if (topBarBadge) {
                topBarBadge.textContent = count;
                topBarBadge.style.display = 'block';
            }
        } else {
            if (topBarBadge) {
                topBarBadge.style.display = 'none';
            }
        }

        // Update sidebar badge
        const sidebarBadge = document.querySelector('.nav-badge');
        if (count > 0) {
            if (sidebarBadge) {
                sidebarBadge.textContent = count;
                sidebarBadge.style.display = 'block';
            }
        } else {
            if (sidebarBadge) {
                sidebarBadge.style.display = 'none';
            }
        }
    }

    showSuccess(title, message) {
        if (typeof showSuccess === 'function') {
            showSuccess(title, message);
        } else {
            alert(`${title}: ${message}`);
        }
    }

    showError(title, message) {
        if (typeof showError === 'function') {
            showError(title, message);
        } else {
            alert(`${title}: ${message}`);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    try {
        new DashboardNotifications();
    } catch (error) {
        console.error('Error initializing DashboardNotifications:', error);
    }
});

// Add CSS for slide out animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOutRight {
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
