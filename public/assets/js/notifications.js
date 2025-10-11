/**
 * Professional Notification System
 * Displays toast notifications in the top-right corner
 */
if (typeof NotificationSystem === 'undefined') {
class NotificationSystem {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('notification-container')) {
            this.container = document.createElement('div');
            this.container.id = 'notification-container';
            this.container.className = 'notification-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('notification-container');
        }
    }

    /**
     * Show a notification
     * @param {string} type - success, error, warning, info
     * @param {string} title - Notification title
     * @param {string} message - Notification message
     * @param {object} options - Additional options
     */
    show(type, title, message, options = {}) {
        const {
            duration = 5000,
            closable = true,
            autoClose = true
        } = options;

        const notification = this.createNotification(type, title, message, closable);
        this.container.appendChild(notification);

        // Trigger animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Auto close
        if (autoClose) {
            this.autoClose(notification, duration);
        }

        return notification;
    }

    /**
     * Create notification element
     */
    createNotification(type, title, message, closable) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;

        const icon = this.getIcon(type);

        notification.innerHTML = `
            <div class="notification-icon">${icon}</div>
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            ${closable ? '<button class="notification-close" onclick="this.parentElement.remove()">&times;</button>' : ''}
            <div class="notification-progress"></div>
        `;

        return notification;
    }

    /**
     * Get icon for notification type
     */
    getIcon(type) {
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        return icons[type] || 'ℹ';
    }

    /**
     * Auto close notification
     */
    autoClose(notification, duration) {
        const progressBar = notification.querySelector('.notification-progress');

        // Animate progress bar
        progressBar.style.width = '100%';
        progressBar.style.transition = `width ${duration}ms linear`;

        // Remove notification after duration
        setTimeout(() => {
            this.hide(notification);
        }, duration);
    }

    /**
     * Hide notification with animation
     */
    hide(notification) {
        notification.classList.add('hide');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 400);
    }

    /**
     * Clear all notifications
     */
    clear() {
        const notifications = this.container.querySelectorAll('.notification');
        notifications.forEach(notification => {
            this.hide(notification);
        });
    }

    /**
     * Success notification
     */
    success(title, message, options = {}) {
        return this.show('success', title, message, options);
    }

    /**
     * Error notification
     */
    error(title, message, options = {}) {
        return this.show('error', title, message, options);
    }

    /**
     * Warning notification
     */
    warning(title, message, options = {}) {
        return this.show('warning', title, message, options);
    }

    /**
     * Info notification
     */
    info(title, message, options = {}) {
        return this.show('info', title, message, options);
    }
}

// Initialize notification system
const notifications = new NotificationSystem();
}

// Global functions for easy access
window.showNotification = (type, title, message, options) => {
    return notifications.show(type, title, message, options);
};

window.showSuccess = (title, message, options) => {
    return notifications.success(title, message, options);
};

window.showError = (title, message, options) => {
    return notifications.error(title, message, options);
};

window.showWarning = (title, message, options) => {
    return notifications.warning(title, message, options);
};

window.showInfo = (title, message, options) => {
    return notifications.info(title, message, options);
};

// Handle Laravel session messages
document.addEventListener('DOMContentLoaded', function() {
    // Check for success message
    const successMessage = document.querySelector('[data-success]');
    if (successMessage) {
        const message = successMessage.getAttribute('data-success');
        notifications.success('نجح!', message);
    }

    // Check for error message
    const errorMessage = document.querySelector('[data-error]');
    if (errorMessage) {
        const message = errorMessage.getAttribute('data-error');
        notifications.error('خطأ!', message);
    }

    // Check for warning message
    const warningMessage = document.querySelector('[data-warning]');
    if (warningMessage) {
        const message = warningMessage.getAttribute('data-warning');
        notifications.warning('تحذير!', message);
    }

    // Check for info message
    const infoMessage = document.querySelector('[data-info]');
    if (infoMessage) {
        const message = infoMessage.getAttribute('data-info');
        notifications.info('معلومة', message);
    }
});
