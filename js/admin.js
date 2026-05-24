// Check if admin is logged in via PHP session
function checkAuth() {
    if (window.location.href.includes('login.html')) return;
    fetch('../php/check_admin.php')
        .then(r => r.json())
        .then(data => {
            if (!data.logged_in) window.location.href = 'login.html';
            else initPage();
        })
        .catch(() => window.location.href = 'login.html');
}

// Called after auth confirmed — runs page-specific init
function initPage() {
    if (window.location.href.includes('dashboard.html')) loadDashboardData();
    else if (window.location.href.includes('orders.html')) loadOrders();
    else if (window.location.href.includes('messages.html')) loadMessages();
    else if (window.location.href.includes('products.html')) loadProducts();
}

document.addEventListener('DOMContentLoaded', checkAuth);

// Handle login
function handleLogin(event) {
    event.preventDefault();
    // Clear previous errors
    document.querySelectorAll('.field-error').forEach(e => e.remove());
    document.querySelectorAll('.input-error').forEach(e => e.classList.remove('input-error'));

    const form = document.getElementById('adminLoginForm');
    const formData = new FormData(form);
    fetch('../php/admin_auth.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'dashboard.html';
            } else {
                if (data.field) {
                    const input = document.getElementById(data.field);
                    if (input) {
                        input.classList.add('input-error');
                        const err = document.createElement('p');
                        err.className = 'field-error';
                        err.textContent = data.message;
                        input.parentNode.appendChild(err);
                        input.focus();
                    }
                } else {
                    showToast(data.message || 'Login failed', 'error');
                }
            }
        })
        .catch(() => showToast('Login failed. Check your connection.', 'error'));
    return false;
}

// Handle logout
function handleLogout() {
    fetch('../php/admin_auth.php?logout=1').finally(() => {
        window.location.href = 'login.html';
    });
}

// Load dashboard data
function loadDashboardData() {
    fetch('../php/admin_data.php?action=dashboard_stats')
        .then(response => response.json())
        .then(data => {
            updateDashboardStats(data);
            createEarningsChart(data.monthly_earnings);
            createLocationChart(data.location_stats);
            loadRecentOrders();
        });
}

// Update dashboard statistics
function updateDashboardStats(data) {
    document.getElementById('totalOrders').textContent = data.total_orders;
    document.getElementById('totalEarnings').textContent = '৳' + data.total_earnings;
    document.getElementById('pendingOrders').textContent = data.pending_orders;
    document.getElementById('completedOrders').textContent = data.completed_orders;
}

// Create earnings chart
function createEarningsChart(data) {
    const ctx = document.getElementById('earningsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(item => item.month),
            datasets: [{
                label: 'Monthly Earnings',
                data: data.map(item => item.total),
                borderColor: '#3498db',
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Create location distribution chart
function createLocationChart(data) {
    const ctx = document.getElementById('locationChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.map(item => item.delivery_location === 'inside' ? 'Inside Dhaka' : 'Outside Dhaka'),
            datasets: [{
                data: data.map(item => item.count),
                backgroundColor: ['#3498db', '#2ecc71']
            }]
        },
        options: {
            responsive: true
        }
    });
}

// Load orders data
function loadOrders() {
    fetch('../php/admin_data.php?action=all_orders')
        .then(response => response.json())
        .then(data => {
            displayOrders(data.orders);
        });
}

// Display orders in table
function displayOrders(orders) {
    const tbody = document.getElementById('ordersTableBody');
    tbody.innerHTML = orders.map(order => `
        <tr>
            <td>${order.id}</td>
            <td>${order.customer_name}</td>
            <td>${order.phone}</td>
            <td>${order.address}</td>
            <td>৳${order.total_amount}</td>
            <td>
                <span class="status-badge status-${order.status}">
                    ${order.status}
                </span>
            </td>
            <td>${order.delivery_location}</td>
            <td>${new Date(order.order_date).toLocaleDateString()}</td>
            <td>
                ${order.status === 'pending' ? 
                    `<button onclick="completeOrder(${order.id})" class="action-btn complete-btn">
                        Complete
                    </button>` : 
                    ''
                }
            </td>
        </tr>
    `).join('');
}

// Complete order
function completeOrder(orderId) {
    const formData = new FormData();
    formData.append('order_id', orderId);
    
    fetch('../php/admin_data.php?action=complete_order', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadOrders();
            showToast('Order marked as completed');
        }
    });
}

// Load messages
function loadMessages() {
    fetch('../php/admin_data.php?action=messages')
        .then(response => response.json())
        .then(data => {
            displayMessages(data.messages);
        });
}

// Display messages
function displayMessages(messages) {
    const container = document.getElementById('messagesList');
    container.innerHTML = messages.map(message => `
        <div class="message-card">
            <div class="message-header">
                <div class="message-info">
                    <span><i class="fas fa-user"></i> ${message.name}</span>
                    <span><i class="fas fa-phone"></i> ${message.phone}</span>
                    <span><i class="fas fa-envelope"></i> ${message.email || 'N/A'}</span>
                </div>
                <div class="message-date">
                    ${new Date(message.created_at).toLocaleString()}
                </div>
            </div>
            <div class="message-content">
                ${message.message}
            </div>
        </div>
    `).join('');
}

// Toast notification
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

// ── Product Management ──────────────────────────────────────────────────────

function loadProducts() {
    fetch('../php/admin_products.php?action=list')
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const tbody = document.getElementById('productsTableBody');
            tbody.innerHTML = data.products.map(p => `
                <tr>
                    <td>${p.id}</td>
                    <td><img src="${p.image}" alt="${p.name}" style="width:50px;height:50px;object-fit:cover;border-radius:5px;"></td>
                    <td>${p.name}</td>
                    <td>${p.category}</td>
                    <td>৳${parseFloat(p.price).toLocaleString()}</td>
                    <td><span class="status-badge status-${p.status}">${p.status}</span></td>
                    <td>${p.rating}</td>
                    <td>
                        <button class="action-btn edit-btn" onclick="editProduct(${p.id},'${escHtml(p.name)}','${escHtml(p.description)}',${p.price},'${escHtml(p.image)}','${p.category}','${p.status}',${p.rating})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete-btn" onclick="deleteProduct(${p.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        });

    // populate category datalist
    fetch('../php/admin_products.php?action=categories')
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const dl = document.getElementById('categoryList');
            if (dl) dl.innerHTML = data.categories.map(c => `<option value="${c}">`).join('');
        });
}

function escHtml(str) {
    return String(str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

function openProductModal(title = 'Add Product') {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('productModal').classList.remove('hidden');
}

function closeProductModal() {
    document.getElementById('productModal').classList.add('hidden');
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
}

function editProduct(id, name, description, price, image, category, status, rating) {
    document.getElementById('productId').value = id;
    document.getElementById('productName').value = name;
    document.getElementById('productDescription').value = description;
    document.getElementById('productPrice').value = price;
    document.getElementById('productImage').value = image;
    document.getElementById('productCategory').value = category;
    document.getElementById('productStatus').value = status;
    document.getElementById('productRating').value = rating;
    openProductModal('Edit Product');
}

function saveProduct(event) {
    event.preventDefault();
    const id = document.getElementById('productId').value;
    const formData = new FormData();
    formData.append('action', id ? 'edit' : 'add');
    if (id) formData.append('id', id);
    formData.append('name',        document.getElementById('productName').value);
    formData.append('description', document.getElementById('productDescription').value);
    formData.append('price',       document.getElementById('productPrice').value);
    formData.append('image',       document.getElementById('productImage').value);
    formData.append('category',    document.getElementById('productCategory').value);
    formData.append('status',      document.getElementById('productStatus').value);
    formData.append('rating',      document.getElementById('productRating').value);

    fetch('../php/admin_products.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(id ? 'Product updated' : 'Product added', 'success');
                closeProductModal();
                loadProducts();
            } else {
                showToast(data.message || 'Failed to save', 'error');
            }
        });
}

function deleteProduct(id) {
    if (!confirm('Delete this product?')) return;
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);
    fetch('../php/admin_products.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) { showToast('Product deleted', 'success'); loadProducts(); }
            else showToast('Failed to delete', 'error');
        });
}
