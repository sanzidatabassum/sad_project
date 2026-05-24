<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../config/db.php';

$medicines = mysqli_query($conn, "SELECT * FROM medicines WHERE stock_qty > 0 ORDER BY medicine_name ASC");
$customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .item-row { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>New Sale / Billing</h3>
        <div>
            <a href="list.php" class="btn btn-secondary">View Sales</a>
            <a href="../dashboard.php" class="btn btn-secondary ms-2">Dashboard</a>
        </div>
    </div>

    <form method="POST" action="save.php">
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Customer</label>
                <select name="customer_id" class="form-control">
                    <option value="">-- Walk-in Customer --</option>
                    <?php while ($c = mysqli_fetch_assoc($customers)): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?> - <?= $c['phone'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label>Customer Name (if not registered)</label>
                <input type="text" name="customer_name" class="form-control" placeholder="Walk-in customer name">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control" required>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="bkash">bKash</option>
                    <option value="nagad">Nagad</option>
                </select>
            </div>
            <div class="col-md-6">
                <label>Discount (৳)</label>
                <input type="number" name="discount" class="form-control" value="0" min="0" id="discount">
            </div>
        </div>

        <h5>Medicine Items</h5>
        <div id="items">
            <div class="item-row row" id="item-1">
                <div class="col-md-5">
                    <label>Medicine</label>
                    <select name="medicine_id[]" class="form-control medicine-select" onchange="setPrice(this)">
                        <option value="">-- Select Medicine --</option>
                        <?php
                        mysqli_data_seek($medicines, 0);
                        while ($m = mysqli_fetch_assoc($medicines)):
                        ?>
                            <option value="<?= $m['id'] ?>" data-price="<?= $m['unit_price'] ?>" data-stock="<?= $m['stock_qty'] ?>">
                                <?= htmlspecialchars($m['medicine_name']) ?> (Stock: <?= $m['stock_qty'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Qty</label>
                    <input type="number" name="qty[]" class="form-control qty-input" value="1" min="1" onchange="calculateTotal()">
                </div>
                <div class="col-md-2">
                    <label>Unit Price</label>
                    <input type="number" name="unit_price[]" class="form-control price-input" step="0.01" readonly>
                </div>
                <div class="col-md-2">
                    <label>Subtotal</label>
                    <input type="number" name="subtotal[]" class="form-control subtotal-input" step="0.01" readonly>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger" onclick="removeItem(this)">X</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addItem()">+ Add Medicine</button>

        <div class="row">
            <div class="col-md-4 offset-md-8">
                <table class="table table-bordered">
                    <tr><td>Subtotal</td><td>৳<span id="gross-total">0.00</span></td></tr>
                    <tr><td>Discount</td><td>৳<span id="discount-display">0.00</span></td></tr>
                    <tr class="table-success"><td><strong>Total</strong></td><td><strong>৳<span id="net-total">0.00</span></strong></td></tr>
                </table>
                <input type="hidden" name="total_amount" id="total_amount">
                <button type="submit" class="btn btn-success w-100 btn-lg">Generate Invoice</button>
            </div>
        </div>
    </form>
</div>

<script>
let itemCount = 1;

function setPrice(select) {
    const row = select.closest('.item-row');
    const option = select.options[select.selectedIndex];
    const price = option.getAttribute('data-price') || 0;
    row.querySelector('.price-input').value = parseFloat(price).toFixed(2);
    calculateTotal();
}

function calculateTotal() {
    let gross = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty   = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const sub   = qty * price;
        row.querySelector('.subtotal-input').value = sub.toFixed(2);
        gross += sub;
    });
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const net = gross - discount;
    document.getElementById('gross-total').innerText    = gross.toFixed(2);
    document.getElementById('discount-display').innerText = discount.toFixed(2);
    document.getElementById('net-total').innerText      = net.toFixed(2);
    document.getElementById('total_amount').value       = net.toFixed(2);
}

function addItem() {
    itemCount++;
    const template = document.getElementById('item-1').cloneNode(true);
    template.id = 'item-' + itemCount;
    template.querySelectorAll('input').forEach(i => i.value = i.type === 'number' ? (i.name.includes('qty') ? 1 : '') : '');
    template.querySelector('select').selectedIndex = 0;
    document.getElementById('items').appendChild(template);
}

function removeItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('.item-row').remove();
        calculateTotal();
    }
}

document.getElementById('discount').addEventListener('input', calculateTotal);
</script>
</body>
</html>