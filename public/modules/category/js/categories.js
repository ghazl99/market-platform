// Categories page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    const categoriesGrid = document.getElementById('categoriesGrid');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            filterCategories(query);
        });
    }

    // Category actions - Delete handled by SweetAlert2 in the main template
    // No need for additional delete handling here

    // Add hover effects
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

function filterCategories(query) {
    const categories = document.querySelectorAll('.category-card');
    categories.forEach(category => {
        const name = category.querySelector('.category-name').textContent.toLowerCase();
        const description = category.querySelector('.category-description').textContent.toLowerCase();

        if (name.includes(query) || description.includes(query)) {
            category.style.display = 'block';
        } else {
            category.style.display = 'none';
        }
    });
}

function handleCategoryAction(action, categoryName) {
    switch(action) {
        case 'view':
            console.log('Viewing category:', categoryName);
            // Implement view functionality
            break;
        case 'edit':
            console.log('Editing category:', categoryName);
            // Implement edit functionality
            break;
        case 'delete':
            console.log('Delete action triggered for:', categoryName);
            // Delete functionality handled by SweetAlert2 in main template
            break;
    }
}

// Add smooth animations
function addSmoothAnimations() {
    const cards = document.querySelectorAll('.category-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Initialize animations when page loads
window.addEventListener('load', addSmoothAnimations);
