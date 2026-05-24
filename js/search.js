let searchTimeout;
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
const searchButton = document.getElementById('searchButton');

// Add event listeners
searchInput.addEventListener('input', handleSearch);
searchButton.addEventListener('click', handleSearch);
document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-container')) {
        searchResults.classList.remove('active');
    }
});

searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        handleSearch(e);
    }
});

function handleSearch(event) {
    if (event) {
        event.preventDefault();
    }
    
    const query = searchInput.value.trim();
    
    // Clear previous timeout
    clearTimeout(searchTimeout);
    
    if (query.length < 2) {
        searchResults.classList.remove('active');
        return;
    }
    
    // Set new timeout to prevent too many requests
    searchTimeout = setTimeout(() => {
        fetchSearchResults(query);
    }, 300);
}

function fetchSearchResults(query) {
    const baseUrl = window.location.pathname.includes('pages') ? '../' : '';
    fetch(`${baseUrl}php/search.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Error:', error);
            searchResults.innerHTML = '<div class="no-results">Error loading results</div>';
            searchResults.classList.add('active');
        });
}

function displaySearchResults(results) {
    if (!results.length) {
        searchResults.innerHTML = '<div class="no-results">No products found</div>';
    } else {
        const baseUrl = window.location.pathname.includes('pages') ? '' : 'pages/';
        searchResults.innerHTML = results.map(product => `
            <div class="search-result-item" onclick="goToProduct(${product.id})">
                <img src="${product.image}" alt="${product.name}" onerror="this.src='${baseUrl}images/placeholder.jpg'">
                <div class="search-result-info">
                    <h4>${product.name}</h4>
                    <p>৳${product.price}</p>
                </div>
            </div>
        `).join('');
    }
    
    searchResults.classList.add('active');
}

function goToProduct(productId) {
    const baseUrl = window.location.pathname.includes('pages') ? '' : 'pages/';
    window.location.href = `${baseUrl}product.html?id=${productId}`;
}

// Add this to handle search in shop page
if (window.location.pathname.includes('shop.html')) {
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('search');
    if (searchQuery) {
        searchInput.value = searchQuery;
        handleSearch();
    }
} 