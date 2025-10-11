// Products Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('.search-filter-content .search-box input');
    const productsGrid = document.getElementById('productsGrid');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            filterProducts(query);
        });
    }

    // Filter functionality
    const filterBtn = document.querySelector('.filter-btn');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            applyFilters();
        });
    }

    // Product actions - only handle delete buttons, let view and edit work naturally
    document.querySelectorAll('.action-btn.delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Delete buttons are handled by the main page script
            return;
        });
    });

    // Pagination
    document.querySelectorAll('.pagination-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.disabled && !this.classList.contains('active')) {
                // Remove active class from all buttons
                document.querySelectorAll('.pagination-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                // Load page content
                loadPage(parseInt(this.textContent.trim()));
            }
        });
    });

    // Delete confirmation is handled in the main page script
});

function filterProducts(query) {
    const products = document.querySelectorAll('.product-card');
    let found = false;

    products.forEach(product => {
        const name = product.querySelector('.product-name').textContent.toLowerCase();
        const description = product.querySelector('.product-description').textContent.toLowerCase();

        if (name.includes(query) || description.includes(query)) {
            product.style.display = 'block';
            found = true;
        } else {
            product.style.display = 'none';
        }
    });

    // Show/hide empty state
    const emptyState = document.querySelector('.empty-state');
    if (emptyState) {
        if (found || query === '') {
            emptyState.style.display = 'none';
        } else {
            emptyState.style.display = 'block';
        }
    }
}

function applyFilters() {
    const categoryFilter = document.querySelector('.search-filter-content .filter-select:first-of-type').value;
    const statusFilter = document.querySelector('.search-filter-content .filter-select:last-of-type').value;

    console.log('Applying filters:', { categoryFilter, statusFilter });

    // In a real Laravel application, you would send an AJAX request to the server
    // or reload the page with filter parameters
    if (categoryFilter || statusFilter) {
        // Reload page with filters
        const url = new URL(window.location);
        if (categoryFilter) url.searchParams.set('category', categoryFilter);
        if (statusFilter) url.searchParams.set('status', statusFilter);
        window.location.href = url.toString();
    }
}

// Removed handleProductAction function as it's no longer needed

function loadPage(pageNumber) {
    console.log('Loading page:', pageNumber);
    // In a Laravel application, this would reload the page with the page parameter
    const url = new URL(window.location);
    url.searchParams.set('page', pageNumber);
    window.location.href = url.toString();
}

// Auto-refresh functionality (optional)
function autoRefresh() {
    // Check for new products every 30 seconds
    setInterval(() => {
        // In a real application, you would make an AJAX request to check for updates
        console.log('Checking for updates...');
    }, 30000);
}

// Initialize auto-refresh if needed
// autoRefresh();
