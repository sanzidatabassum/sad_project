// Products loaded from database
let products = [];

// Cart functionality
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

// Toast notification system
function showToast(message, type = 'success') {
    const existing = document.querySelector('.popup-toast');
    if (existing) existing.remove();

    const titles = { success: 'Success', error: 'Error', warning: 'Warning', info: 'Info' };
    const icons  = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };

    const toast = document.createElement('div');
    toast.className = `popup-toast popup-toast--${type}`;
    toast.innerHTML = `
        <i class="fas ${icons[type] || icons.success} toast-icon"></i>
        <div class="toast-body">
            <div class="toast-title">${titles[type] || 'Notice'}</div>
            <div class="toast-msg">${message}</div>
        </div>
        <button class="toast-close" onclick="this.closest('.popup-toast').remove()"><i class="fas fa-times"></i></button>
        <div class="toast-progress"></div>
    `;
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('popup-toast--show'));
    setTimeout(() => {
        toast.classList.remove('popup-toast--show');
        setTimeout(() => toast.remove(), 450);
    }, 3000);
}

// Example usage:
// showToast('Success message', 'success');
// showToast('Error message', 'error');
// showToast('Warning message', 'warning');
// showToast('Info message', 'info');

// Update all your existing showToast calls to use the new types
function showSuccessToast(message) {
    showToast(message, 'success');
}

function showErrorToast(message) {
    showToast(message, 'error');
}

function showWarningToast(message) {
    showToast(message, 'warning');
}

function showInfoToast(message) {
    showToast(message, 'info');
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const el = document.querySelector('.cart-count');
    if (el) el.textContent = cart.length;
}

function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.push({...product, quantity: 1});
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCounts();
        showToast(`${product.name} added to cart`, 'success');
    }
}

function addToWishlist(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {
        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        if (wishlist.some(item => item.id === productId)) {
            showToast('Product is already in your wishlist', 'info');
            return;
        }
        wishlist.push(product);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        updateCounts();
        showToast(`${product.name} added to wishlist`, 'success');
    }
}

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    loadAndRenderProducts();
});

function renderProducts() {
    const productGrids = document.querySelectorAll('.product-grid');
    
    productGrids.forEach(grid => {
        const section = grid.parentElement.className;
        let productsToShow = [];
        
        switch(section) {
            case 'upcoming-products':
                productsToShow = products.filter(p => p.upcoming);
                break;
            case 'new-arrivals':
                productsToShow = products.filter(p => p.isNew);
                break;
            case 'top-rated':
                productsToShow = products.sort((a, b) => b.rating - a.rating).slice(0, 4);
                break;
            case 'all-products':
                productsToShow = products;
                break;
        }
        
        grid.innerHTML = productsToShow.map(product => `
            <div class="product-card" data-category="${product.category}">
                <div class="product-badges">
                    ${product.isNew ? '<span class="badge new">New</span>' : ''}
                    ${product.upcoming ? '<span class="badge upcoming">Upcoming</span>' : ''}
                </div>
                <div class="product-image-container">
                    <img 
                        src="${product.image}" 
                        alt="${product.name}" 
                        class="product-image"
                        onerror="handleImageError(this)"
                    >
                </div>
                <div class="product-info">
                    <span class="product-category">${product.category}</span>
                    <h3>${product.name}</h3>
                    <div class="rating">
                        ${generateStarRating(product.rating)}
                        <span class="rating-number">(${product.rating})</span>
                    </div>
                    <p class="product-description">${product.description}</p>
                    <p class="product-price">৳${product.price.toLocaleString()}</p>
                    <div class="product-buttons">
                        <button onclick="handleAddToCart(event, ${product.id})" class="add-to-cart">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button onclick="handleAddToWishlist(event, ${product.id})" class="add-to-wishlist">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    });
}

// Helper function to generate star rating
function generateStarRating(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    let stars = '';
    
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    const emptyStars = 5 - Math.ceil(rating);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    
    return stars;
}

// Add this function to handle image loading errors
function handleImageError(img) {
    const fallbackImages = {
        headphones: 'https://m.media-amazon.com/images/I/71o8Q5XJS5L._AC_SL1500_.jpg',
        earbuds: 'https://m.media-amazon.com/images/I/71bhWgQK-cL._AC_SL1500_.jpg',
        smartwatch: 'https://m.media-amazon.com/images/I/71XMTLtZd5L._AC_SL1500_.jpg',
        powerbank: 'https://m.media-amazon.com/images/I/71lVwl3q-kL._AC_SL1500_.jpg'
    };
    
    const category = img.closest('.product-card').dataset.category;
    img.src = fallbackImages[category];
}

// Add this function to show product details
function showProductDetails(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    const modal = document.createElement('div');
    modal.className = 'product-modal';
    
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="product-details">
                <div class="product-details-left">
                    <div class="product-image-large">
                        <img src="${product.image}" alt="${product.name}" onerror="this.src='${getFallbackImage(product.category)}'">
                    </div>
                    <div class="product-actions">
                        <button onclick="addToCart(${product.id})" class="add-to-cart">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button onclick="addToWishlist(${product.id})" class="add-to-wishlist">
                            <i class="fas fa-heart"></i> Add to Wishlist
                        </button>
                    </div>
                </div>
                <div class="product-details-right">
                    <h2>${product.name}</h2>
                    <div class="rating">
                        ${generateStarRating(product.rating)}
                        <span class="rating-number">(${product.rating})</span>
                    </div>
                    <p class="product-price">৳${product.price.toLocaleString()}</p>
                    
                    <div class="product-tabs">
                        <div class="tab-buttons">
                            <button class="tab-btn active" data-tab="description">Description</button>
                            <button class="tab-btn" data-tab="specifications">Specifications</button>
                            <button class="tab-btn" data-tab="reviews">Reviews</button>
                        </div>
                        
                        <div class="tab-content active" id="description">
                            <p>${product.description}</p>
                        </div>
                        
                        <div class="tab-content" id="specifications">
                            ${Object.entries(product.specifications).map(([key, value]) => `
                                <div class="spec-item">
                                    <span class="spec-label">${key.charAt(0).toUpperCase() + key.slice(1)}:</span>
                                    <span class="spec-value">${value}</span>
                                </div>
                            `).join('')}
                        </div>
                        
                        <div class="tab-content" id="reviews">
                            ${product.reviews ? product.reviews.map(review => `
                                <div class="review-item">
                                    <div class="review-header">
                                        <span class="review-user">${review.user}</span>
                                        <div class="review-rating">
                                            ${generateStarRating(review.rating)}
                                        </div>
                                        <span class="review-date">${review.date}</span>
                                    </div>
                                    <p class="review-comment">${review.comment}</p>
                                </div>
                            `).join('') : '<p>No reviews yet</p>'}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    
    // Handle tabs
    const tabButtons = modal.querySelectorAll('.tab-btn');
    const tabContents = modal.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tab = button.dataset.tab;
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            button.classList.add('active');
            modal.querySelector(`#${tab}`).classList.add('active');
        });
    });

    // Close modal
    const closeBtn = modal.querySelector('.close-modal');
    closeBtn.onclick = () => modal.remove();
    modal.onclick = (e) => {
        if (e.target === modal) modal.remove();
    };
}

// Helper function for fallback images
function getFallbackImage(category) {
    const fallbacks = {
        headphones: 'https://m.media-amazon.com/images/I/71o8Q5XJS5L._AC_SL1500_.jpg',
        earbuds: 'https://m.media-amazon.com/images/I/71bhWgQK-cL._AC_SL1500_.jpg',
        smartwatch: 'https://m.media-amazon.com/images/I/71XMTLtZd5L._AC_SL1500_.jpg',
        powerbank: 'https://m.media-amazon.com/images/I/71lVwl3q-kL._AC_SL1500_.jpg'
    };
    return fallbacks[category];
}

// Add this function to update both cart and wishlist counts
function updateCounts() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const cartEl = document.querySelector('.cart-count');
    const wishlistEl = document.querySelector('.wishlist-count');
    if (cartEl) cartEl.textContent = cart.length;
    if (wishlistEl) wishlistEl.textContent = wishlist.length;
}

// Initialize counts on page load
document.addEventListener('DOMContentLoaded', () => {
    updateCounts();
    loadAndRenderProducts();
});

async function loadAndRenderProducts() {
    try {
        const base = window.location.pathname.includes('/pages/') ? '../' : '';
        const res = await fetch(base + 'php/get_products.php');
        const data = await res.json();
        if (data.success) {
            // Map DB status field to isNew/upcoming flags expected by renderProducts
            products = data.products.map(p => ({
                ...p,
                isNew: p.status === 'available',
                upcoming: p.status === 'upcoming'
            }));
        }
    } catch (e) {
        console.error('Failed to load products:', e);
    }
    renderProducts();
}

// Add these new handler functions
function handleAddToCart(event, productId) {
    event.preventDefault();
    event.stopPropagation();
    addToCart(productId);
}

function handleAddToWishlist(event, productId) {
    event.preventDefault();
    event.stopPropagation();
    addToWishlist(productId);
}

// Add this function
function goToProduct(productId) {
    const baseUrl = window.location.pathname.includes('pages') ? '' : 'pages/';
    window.location.href = `${baseUrl}product.html?id=${productId}`;
}

// Update your product card creation to include onclick
function createProductCard(product) {
    return `
        <div class="product-card" onclick="goToProduct(${product.id})">
            <!-- Rest of your product card HTML -->
        </div>
    `;
} 