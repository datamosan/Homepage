<?php
session_start();

// Example cart structure: $_SESSION['cart'] = [['name'=>'Item 1', 'qty'=>2, 'price'=>100, 'image'=>'img1.jpg'], ...];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

function cart_total($cart)
{
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['qty'] * $item['price'];
    }
    return $total;
}

// Bank details
$bank_details = [
    'Bank Name' => 'Metrobank',
    'Account Name' => 'Marie Danielle Necio',
    'Account Number' => '288 328 830 7629',
    'GCash' => '0915 007 7783'
];

// Handle form submission (upload receipt)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload and order processing here
    // ...
    echo "<script>alert('Order placed! Please wait for confirmation.'); window.location='order.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Place Order - Dhen's Kitchen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<!-- Header -->
    <header>
        <div class="header-bar">
            <div class="page-title">About Us</div>
        </div>
        <nav class="main-nav">
            <div class="nav-links">
                <a href="about.php" class="nav-item active">About Us</a>
                <a href="menu.php" class="nav-item">Menu</a>
                <a href="order.php" class="nav-item">Order Now</a>
            </div>
            <a href="index.php" class="logo-container">
                <img src="logo.png" alt="Dhen's Kitchen Logo" class="logo-img">
            </a>
            <div class="nav-links">
                <a href="faq.php" class="nav-item">FAQs</a>
                <a href="contact.php" class="nav-item">Contact Us</a>
                <?php if (isset($_SESSION['first_name'])): ?>
                    <div class="nav-dropdown">
                        <button class="nav-item nav-dropdown-btn">
                            Hi, <?php echo htmlspecialchars($_SESSION['first_name']); ?> <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="nav-dropdown-content">
                            <a href="profilepage.php">Profile</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="auth.html" class="nav-item">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="order-main">
        <div style="position: relative; margin-bottom: 30px;">
            <!-- Back Button: stays left -->
            <a href="order.php" class="cta-button"
                style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); display: flex; align-items: center; gap: 8px; background-color: var(--white); color: var(--teal); border: none; padding: 8px 18px; font-size: 1rem; box-shadow: none; width: auto;">
                <span style="font-size: 1.3em;">&#8592;</span>
                <span>Back to Order Page</span>
            </a>
            <!-- Centered Title -->
            <h2 class="order-subtitle" style="margin: 0; text-align: center;">Review Your Order</h2>
        </div>
        <form method="post" enctype="multipart/form-data" class="checkout-form">
            <div class="checkout-wrapper">
                <!-- Cart Items Table -->
                <div class="checkout-cart">
                    <h3 class="cart-title">Your Cart</h3>
                    <div id="cart-table-container"></div>
                </div>
                <!-- Summary / Shipping / Payment -->
                <div class="checkout-summary">
                    <div class="summary-section">
                        <div class="summary-title">Cart Totals</div>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₱<?= number_format(cart_total($cart), 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>
                                <select name="shipping"
                                    style="padding:4px 10px; border-radius:6px; border:1px solid var(--teal); color:var(--teal);">
                                    <option>Self-Pickup</option>
                                    <option>Delivery (Self-Arrange)</option>
                                </select>
                            </span>
                        </div>
                        <div class="summary-total">
                            Total: ₱<span id="order-total"><?= number_format(cart_total($cart), 2) ?></span>
                        </div>
                    </div>
                    <div class="summary-section">
                        <div class="summary-title">Bank Details</div>
                        <ul class="bank-details-list">
                            <?php foreach ($bank_details as $label => $value): ?>
                                <li><strong><?= $label ?>:</strong> <?= htmlspecialchars($value) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" id="show-qr-btn" class="show-qr-btn">
                            <i class="fas fa-qrcode"></i>Show QR Codes
                        </button>
                        <div id="qr-modal"
                            style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
                            <div
                                style="background:#fff; padding:32px 24px; border-radius:16px; max-width:400px; max-height:95vh; overflow:auto; position:relative; display:flex; flex-direction:column; align-items:center;">
                                <button type="button" onclick="document.getElementById('qr-modal').style.display='none'"
                                    style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5em; color:var(--teal); cursor:pointer;">&times;</button>
                                <h3 style="margin-bottom:18px; color:var(--teal);">Scan to Pay</h3>
                                <div style="display:flex; gap:12px; margin-bottom:18px;">
                                    <button type="button" id="btn-metrobank"
                                        style="padding:6px 18px; border-radius:8px; border:1px solid var(--teal); background:var(--teal); color:#fff; font-weight:bold; cursor:pointer;">Metrobank</button>
                                    <button type="button" id="btn-gcash"
                                        style="padding:6px 18px; border-radius:8px; border:1px solid var(--teal); background:#fff; color:var(--teal); font-weight:bold; cursor:pointer;">GCash</button>
                                </div>
                                <div id="qr-metrobank" style="text-align:center;">
                                    <img src="images/metrobankqr.jpg" alt="Metrobank QR"
                                        style="width:320px; height:320px; object-fit:contain; border:1px solid #eee; border-radius:14px; background:#fafafa;">
                                    <div style="margin-top:12px; font-weight:bold; font-size:1.1em;">Metrobank</div>
                                </div>
                                <div id="qr-gcash" style="display:none; text-align:center;">
                                    <img src="images/gcashqr.jpg" alt="GCash QR"
                                        style="width:320px; height:320px; object-fit:contain; border:1px solid #eee; border-radius:14px; background:#fafafa;">
                                    <div style="margin-top:12px; font-weight:bold; font-size:1.1em;">GCash</div>
                                </div>
                            </div>
                        </div>
                        <p style="color:var(--coral); font-size:0.95rem;">Send your payment to the details above and
                            upload your receipt below.</p>
                    </div>
                    <div class="summary-section">
                        <label class="upload-label" for="receipt">Upload Payment Receipt</label>
                        <input type="file" name="receipt" id="receipt" accept="image/*,application/pdf" required
                            style="margin-bottom:18px;">
                    </div>
                    <button type="submit" class="cta-button" style="width:100%; margin-top:10px;">Place Order</button>
                </div>
            </div>
        </form>
        
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cart = JSON.parse(localStorage.getItem('dhensKitchenCart') || '[]');
            const container = document.getElementById('cart-table-container');
            if (cart.length === 0) {
                container.innerHTML = '<div class="cart-empty">Your cart is empty.</div>';
                return;
            }
            let html = `<table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>`;
            let total = 0;
            cart.forEach(item => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                html += `<tr>
                    <td>
                        ${item.name}${item.size ? ' - ' + item.size : ''}
                    </td>
                    <td>₱${Number(item.price).toFixed(2)}</td>
                    <td>${item.quantity}</td>
                    <td style="color:var(--coral); font-weight:bold;">₱${subtotal.toFixed(2)}</td>
                </tr>`;
            });
            html += `</tbody></table>`;
            container.innerHTML = html;

            // Update summary subtotal and total if present
            const subtotalSpan = document.querySelector('.summary-row span:last-child');
            const totalSpan = document.getElementById('order-total');
            if (subtotalSpan) subtotalSpan.textContent = '₱' + total.toLocaleString(undefined, {minimumFractionDigits:2});
            if (totalSpan) totalSpan.textContent = total.toLocaleString(undefined, {minimumFractionDigits:2});
            // Update total when shipping changes
            const shippingSelect = document.querySelector('select[name="shipping"]');
            if (shippingSelect && totalSpan) {
                shippingSelect.addEventListener('change', function () {
                    const shipping = parseFloat(this.value) || 0;
                    totalSpan.textContent = (total + shipping).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                });
            }
        });

        document.getElementById('show-qr-btn').onclick = function () {
            document.getElementById('qr-modal').style.display = 'flex';
            showQR('metrobank');
        };
        document.getElementById('btn-metrobank').onclick = function () {
            showQR('metrobank');
        };
        document.getElementById('btn-gcash').onclick = function () {
            showQR('gcash');
        };
        function showQR(which) {
            document.getElementById('qr-metrobank').style.display = (which === 'metrobank') ? 'block' : 'none';
            document.getElementById('qr-gcash').style.display = (which === 'gcash') ? 'block' : 'none';
            document.getElementById('btn-metrobank').style.background = (which === 'metrobank') ? 'var(--teal)' : '#fff';
            document.getElementById('btn-metrobank').style.color = (which === 'metrobank') ? '#fff' : 'var(--teal)';
            document.getElementById('btn-gcash').style.background = (which === 'gcash') ? 'var(--teal)' : '#fff';
            document.getElementById('btn-gcash').style.color = (which === 'gcash') ? '#fff' : 'var(--teal)';
        }
    </script>
</body>

</html>