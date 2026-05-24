// Shop page functionality
let filteredProducts = [];
const productGrid = document.querySelector('.product-grid');
const sortSelect = document.getElementById('sortProducts');
const priceRange = document.getElementById('priceRange');
const minPriceInput = document.getElementById('minPrice');
const maxPriceInput = document.getElementById('maxPrice');
const applyFiltersBtn = document.querySelector('.apply-filters');

// Sort products
function sortProducts(products, sortBy) {
    switch(sortBy) {
        case 'price-low':
            return [...products].sort((a, b) => a.price - b.price);
        case 'price-high':
            return [...products].sort((a, b) => b.price - a.price);
        case 'rating':
            return [...products].sort((a, b) => b.rating - a.rating);
        default:
            return products;
    }
}

// Derive brand from product name
function getBrand(product) {
    if (product.brand) return product.brand;
    const n = product.name.toLowerCase();
    if (n.includes('sony'))        return 'sony';
    if (n.includes('apple'))       return 'apple';
    if (n.includes('samsung'))     return 'samsung';
    if (n.includes('anker'))       return 'anker';
    if (n.includes('bose'))        return 'bose';
    if (n.includes('jbl'))         return 'jbl';
    if (n.includes('oneplus'))     return 'oneplus';
    if (n.includes('huawei'))      return 'huawei';
    if (n.includes('xiaomi'))      return 'xiaomi';
    if (n.includes('sennheiser'))  return 'sennheiser';
    if (n.includes('jabra'))       return 'jabra';
    if (n.includes('nothing'))     return 'nothing';
    if (n.includes('garmin'))      return 'garmin';
    if (n.includes('fitbit'))      return 'fitbit';
    if (n.includes('romoss'))      return 'romoss';
    if (n.includes('baseus'))      return 'baseus';
    if (n.includes('ugreen'))      return 'ugreen';
    if (n.includes('mi '))         return 'xiaomi';
    if (n.includes('soundcore'))   return 'soundcore';
    return product.category;
}

// Filter products
function filterProducts() {
    const selectedCategories = [...document.querySelectorAll('input[name="category"]:checked')].map(i => i.value);
    const selectedBrands     = [...document.querySelectorAll('input[name="brand"]:checked')].map(i => i.value);
    const minPrice = Number(minPriceInput.value) || 0;
    const maxPrice = Number(maxPriceInput.value) || Number(priceRange.max);

    filteredProducts = products.filter(product => {
        const categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(product.category);
        const brandMatch    = selectedBrands.length === 0     || selectedBrands.includes(getBrand(product));
        const priceMatch    = product.price >= minPrice && product.price <= maxPrice;
        return categoryMatch && brandMatch && priceMatch;
    });

    filteredProducts = sortProducts(filteredProducts, sortSelect.value);
    renderProducts();
}

// Update price inputs when range changes
priceRange.addEventListener('input', (e) => {
    maxPriceInput.value = e.target.value;
    filterProducts(); // Apply filter immediately when sliding
});

// Event listeners
sortSelect.addEventListener('change', filterProducts);
applyFiltersBtn.addEventListener('click', filterProducts);

// Update the renderProducts function for the shop page
function renderProducts() {
    const productsToShow = filteredProducts;
    
    productGrid.innerHTML = productsToShow.map(product => `
        <div class="product-card" data-category="${product.category}" onclick="showProductDetails(${product.id})">
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
                <div class="product-buttons" onclick="event.stopPropagation()">
                    <button onclick="addToCart(${product.id})" class="add-to-cart">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <button onclick="addToWishlist(${product.id})" class="add-to-wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    // Update product count
    const productCount = document.querySelector('.shop-header h2');
    productCount.textContent = `All Products (${productsToShow.length})`;
}

// Add input event listeners for min/max price inputs
minPriceInput.addEventListener('input', filterProducts);
maxPriceInput.addEventListener('input', filterProducts);

// Initialize the shop page
document.addEventListener('DOMContentLoaded', async () => {
    // Wait for products to load from DB (set by main.js loadAndRenderProducts)
    if (products.length === 0) {
        try {
            const res  = await fetch('../php/get_products.php');
            const data = await res.json();
            if (data.success) {
                products = data.products.map(p => ({
                    ...p,
                    id: parseInt(p.id),
                    price: parseFloat(p.price),
                    rating: parseFloat(p.rating),
                    isNew: p.status === 'available',
                    upcoming: p.status === 'upcoming'
                }));
            }
        } catch(e) { console.error('Failed to load products', e); }
    }

    filteredProducts = [...products];

    // Assign brands
    products.forEach(product => {
        if (!product.brand) {
            const n = product.name.toLowerCase();
            if (n.includes('sony'))    product.brand = 'sony';
            else if (n.includes('bose'))   product.brand = 'bose';
            else if (n.includes('apple'))  product.brand = 'apple';
            else if (n.includes('samsung'))product.brand = 'samsung';
            else if (n.includes('anker'))  product.brand = 'anker';
            else if (n.includes('jbl'))    product.brand = 'jbl';
            else if (n.includes('jabra'))  product.brand = 'jabra';
            else product.brand = product.category;
        }
    });

    // Set price range
    const prices = products.map(p => p.price);
    const max = Math.max(...prices);
    priceRange.max = max;
    maxPriceInput.placeholder = `${max}`;
    maxPriceInput.value = max;

    filterProducts();

    // Live filter on checkbox change
    document.querySelectorAll('input[name="category"], input[name="brand"]')
        .forEach(cb => cb.addEventListener('change', filterProducts));
}); 