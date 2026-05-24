let currentProduct = null;

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    
    if (productId) {
        fetchProductDetails(productId);
    } else {
        window.location.href = 'shop.html';
    }
});

function fetchProductDetails(productId) {
    fetch(`../php/get_product.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentProduct = data.product;
                displayProductDetails(data.product);
            } else {
                showToast('Product not found', 'error');
                setTimeout(() => {
                    window.location.href = 'shop.html';
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error loading product details', 'error');
        });
}

function displayProductDetails(product) {
    document.title = `${product.name} - TryUsBD`;
    document.getElementById('productImage').src = product.image;
    document.getElementById('productName').textContent = product.name;
    document.getElementById('productPrice').textContent = product.price;
    document.getElementById('productCategory').textContent = product.category;
    document.getElementById('productDescription').textContent = product.description;
    document.getElementById('productRating').textContent = product.rating || '0.0';
    document.getElementById('ratingCount').textContent = 
        `(${product.rating_count || 0} reviews)`;
}

function updateQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    const currentQuantity = parseInt(quantityInput.value);
    const newQuantity = Math.max(1, currentQuantity + change);
    quantityInput.value = newQuantity;
}

function addToCart() {
    if (!currentProduct) return;

    const quantity = parseInt(document.getElementById('quantity').value);
    const cartItem = {
        id: currentProduct.id,
        name: currentProduct.name,
        price: parseFloat(currentProduct.price),
        image: currentProduct.image,
        quantity: quantity
    };

    // Get existing cart
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Check if product already exists in cart
    const existingItemIndex = cart.findIndex(item => item.id === cartItem.id);
    
    if (existingItemIndex !== -1) {
        cart[existingItemIndex].quantity += quantity;
    } else {
        cart.push(cartItem);
    }
    
    // Save updated cart
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Update cart count
    updateCartCount();
    
    // Show success message
    showToast('Product added to cart successfully!');
}

function addToWishlist() {
    if (!currentProduct) return;

    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    
    if (wishlist.some(item => item.id === currentProduct.id)) {
        showToast('Product already in wishlist', 'info');
        return;
    }
    
    wishlist.push({
        id: currentProduct.id,
        name: currentProduct.name,
        price: currentProduct.price,
        image: currentProduct.image
    });
    
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    showToast('Product added to wishlist!');
} 