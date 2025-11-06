// Store-specific JavaScript functions for Dashboard
// This file contains store-related functionality for the dashboard

document.addEventListener('DOMContentLoaded', function() {
    // Initialize store-specific features
    initializeStoreFeatures();
});

/**
 * Initialize store-specific features
 */
function initializeStoreFeatures() {
    // Initialize store logo handling
    initializeStoreLogo();
    
    // Initialize store settings
    initializeStoreSettings();
    
    // Initialize any other store-specific features
    console.log('Store dashboard initialized');
}

/**
 * Initialize store logo handling
 */
function initializeStoreLogo() {
    const storeLogo = document.querySelector('.sidebar-logo img');
    if (storeLogo) {
        // Handle logo loading errors
        storeLogo.addEventListener('error', function() {
            this.style.display = 'none';
            const icon = this.nextElementSibling || document.querySelector('.sidebar-logo i');
            if (icon) {
                icon.style.display = 'block';
            }
        });
    }
}

/**
 * Initialize store settings functionality
 */
function initializeStoreSettings() {
    // Add store settings related functionality here
    // This can include form validation, AJAX calls, etc.
}

// Export functions for global access if needed
window.initializeStoreFeatures = initializeStoreFeatures;
