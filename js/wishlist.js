let wishlistItems = JSON.parse(localStorage.getItem('wishlist')) || [];

function renderWishlist() {
    const container = document.querySelector('.wishlist-container');
    const totalItems = document.getElementById('total-items');
    const totalAmount = document.getElementById('total-amount');
    
    container.innerHTML = '';
    
    if (wishlistItems.length === 0) {
        container.innerHTML = '<p class="empty-message">Your wishlist is empty</p>';
        totalItems.textContent = '0';
        totalAmount.textContent = '0';
        return;
    }

    let total = 0;
    wishlistItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.className = 'wishlist-item';
        itemElement.dataset.id = item.id;
        itemElement.innerHTML = `
            <img src="${item.image}" alt="${item.name}">
            <div class="wishlist-item-info">
                <h3>${item.name}</h3>
                <p class="wishlist-item-price">৳${item.price}</p>
            </div>
            <div class="wishlist-item-actions">
                <button class="add-to-cart-btn" onclick="addToCart(${item.id})">
                    <i class="fas fa-shopping-cart"></i>
                </button>
                <button class="remove-btn" onclick="removeFromWishlist(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(itemElement);
        total += item.price;
    });

    totalItems.textContent = wishlistItems.length;
    totalAmount.textContent = total.toFixed(2);
}

function removeFromWishlist(id) {
    const index = wishlistItems.findIndex(item => item.id === parseInt(id));
    if (index !== -1) {
        const removedItem = wishlistItems[index];
        wishlistItems.splice(index, 1);
        localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
        renderWishlist();
        updateCounts();
        showToast(`${removedItem.name} removed from wishlist`, 'info');
    }
}

function addToCart(id) {
    const item = wishlistItems.find(item => item.id === id);
    if (item) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.push({...item, quantity: 1});
        localStorage.setItem('cart', JSON.stringify(cart));
        removeFromWishlist(id);
        updateCounts();
        showToast(`${item.name} moved to cart`, 'success');
        
        setTimeout(() => {
            window.location.href = 'cart.html';
        }, 1500);
    }
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    document.querySelector('.cart-count').textContent = cart.length;
}

// Update the "Add All to Cart" button handler
document.getElementById('order-all-btn').addEventListener('click', function() {
    const btn = this;
    
    if (wishlistItems.length === 0) {
        showToast('Your wishlist is empty', 'error');
        return;
    }

    // Disable the button and show loading state
    btn.disabled = true;
    btn.classList.add('disabled');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Add items to cart with quantity
    wishlistItems.forEach(item => {
        cart.push({...item, quantity: 1});
    });
    
    // Update storage and UI
    localStorage.setItem('cart', JSON.stringify(cart));
    localStorage.removeItem('wishlist');
    wishlistItems = [];
    
    // Show success message
    showToast('All items added to cart');
    
    // Delay redirect slightly to show the message
    setTimeout(() => {
        window.location.href = 'cart.html';
    }, 1000);
});

// Add this CSS for the spinner animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fa-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .fa-spin {
        animation: fa-spin 1s linear infinite;
    }
`;
document.head.appendChild(style);

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    renderWishlist();
    updateCartCount();
}); 