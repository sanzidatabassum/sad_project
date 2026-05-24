document.addEventListener('DOMContentLoaded', () => {
    // Get order details from localStorage
    const orderDetails = JSON.parse(localStorage.getItem('lastOrder'));
    
    if (!orderDetails) {
        window.location.href = '../index.html';
        return;
    }

    // Display order details
    document.getElementById('orderId').textContent = orderDetails.orderId;
    document.getElementById('customerName').textContent = orderDetails.name;
    document.getElementById('customerPhone').textContent = orderDetails.phone;
    document.getElementById('customerEmail').textContent = orderDetails.email || 'N/A';
    document.getElementById('customerAddress').textContent = orderDetails.address;
    document.getElementById('deliveryLocation').textContent = 
        orderDetails.delivery === 'inside' ? 'Inside Dhaka' : 'Outside Dhaka';

    // Display ordered items
    const itemsList = document.getElementById('itemsList');
    orderDetails.items.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'order-item';
        itemDiv.innerHTML = `
            <span>${item.name} × ${item.quantity}</span>
            <span>৳${(item.price * item.quantity).toFixed(2)}</span>
        `;
        itemsList.appendChild(itemDiv);
    });

    // Display totals
    document.getElementById('subtotal').textContent = orderDetails.subtotal;
    document.getElementById('deliveryCharge').textContent = orderDetails.deliveryCharge;
    document.getElementById('total').textContent = orderDetails.total;

    // Clear the order details from localStorage after displaying
    localStorage.removeItem('lastOrder');
});

function printOrder() {
    window.print();
} 