// Image Fallback Handler
document.addEventListener('DOMContentLoaded', function() {
    // Handle broken images
    const images = document.querySelectorAll('.product-image');

    images.forEach(img => {
        img.addEventListener('error', function() {
            // Replace with no-image placeholder
            this.src = '/images/no-image.png';
            this.alt = 'No Image Available';
            if (this.classList) {
                this.classList.add('no-image-fallback');
            }
        });

        // Handle empty or undefined src
        if (!img.src || img.src.includes('undefined') || img.src.includes('null')) {
            img.src = '/images/no-image.png';
            img.alt = 'No Image Available';
            if (img.classList) {
                img.classList.add('no-image-fallback');
            }
        }
    });

    // Handle images that fail to load
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                const newImages = mutation.target.querySelectorAll('.product-image');
                newImages.forEach(img => {
                    img.addEventListener('error', function() {
                        this.src = '/images/no-image.png';
                        this.alt = 'No Image Available';
                        if (this.classList) {
                            this.classList.add('no-image-fallback');
                        }
                    });
                });
            }
        });
    });

    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
