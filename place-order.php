<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.html');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch active cart and items
$cart_items = [];
$cart_total = 0.0;
$cart_id = null;

$conn = new mysqli('localhost', 'root', '', 'decadhen');
if ($conn->connect_error) die('DB error');

$cart_res = $conn->query("SELECT CartID FROM Cart WHERE UserID=$user_id AND Status='active' LIMIT 1");
if ($cart_row = $cart_res->fetch_assoc()) {
    $cart_id = $cart_row['CartID'];
    $items_res = $conn->query(
        "SELECT ci.CartItemID, ci.ProductID, p.ProductName, ci.CartQuantity, ci.UnitPrice, ci.Size
         FROM CartItems ci
         JOIN Products p ON ci.ProductID = p.ProductID
         WHERE ci.CartID = $cart_id"
    );
    while ($row = $items_res->fetch_assoc()) {
        $row['Subtotal'] = $row['CartQuantity'] * $row['UnitPrice'];
        $cart_total += $row['Subtotal'];
        $cart_items[] = $row;
    }
}

// Bank details
$bank_details = [
    'Bank Name' => 'Metrobank',
    'Account Name' => 'Marie Danielle Necio',
    'Account Number' => '288 328 830 7629',
    'GCash' => '0915 007 7783'
];

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cart_items)) {
    // 1. Handle file upload
    $receipt_path = '';
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
        $filename = 'receipt_' . time() . '_' . rand(1000,9999) . '.' . $ext;
        $target = __DIR__ . '/payments/' . $filename;
        if (!is_dir(__DIR__ . '/payments')) mkdir(__DIR__ . '/payments', 0777, true);
        if (move_uploaded_file($_FILES['receipt']['tmp_name'], $target)) {
            $receipt_path = 'payments/' . $filename; // This is what will be saved in the PaymentProof column
        }
    }

    // 2. Insert into orders table (save $receipt_path to PaymentProof)
    $shipping = $conn->real_escape_string($_POST['shipping'] ?? 'Self-Pickup');
    $order_status = 'New';
    $order_deadline = $_POST['order_deadline'] ?? null;
    $order_note = $conn->real_escape_string($_POST['order_note'] ?? '');

    $stmt = $conn->prepare("INSERT INTO orders (UserID, OrderDate, OrderStatus, OrderTotal, ShippingMethod, PaymentProof, OrderDeadline, OrderNote) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssdssss', $user_id, $order_status, $cart_total, $shipping, $receipt_path, $order_deadline, $order_note);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // 3. Insert each cart item into orderdetails (without UnitPrice)
    // Make sure your orderdetails table has a Size column (VARCHAR or similar)
    $stmt = $conn->prepare("INSERT INTO orderdetails (OrderID, ProductID, OrderQuantity, Size) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $item) {
        $stmt->bind_param('iiis', $order_id, $item['ProductID'], $item['CartQuantity'], $item['Size']);
        $stmt->execute();
    }
    $stmt->close();

    // 4. Mark cart as completed and clear cart items
    $conn->query("UPDATE Cart SET Status='completed' WHERE CartID=$cart_id");
    $conn->query("DELETE FROM CartItems WHERE CartID=$cart_id");

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
                    <?php if (empty($cart_items)): ?>
                    <div class="cart-empty">Your cart is empty.</div>
                    <?php else: ?>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['ProductName']) ?></td>
                                <td>₱<?= number_format($item['UnitPrice'], 2) ?></td>
                                <td><?= intval($item['CartQuantity']) ?></td>
                                <td style="color:var(--coral); font-weight:bold;">₱<?= number_format($item['Subtotal'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <!-- Summary / Shipping / Payment -->
                <div class="checkout-summary">
                    <div class="summary-section">
                        <div class="summary-title">Order Summary</div>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₱<?= number_format($cart_total, 2) ?></span>
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
                            Total: ₱<span id="order-total"><?= number_format($cart_total, 2) ?></span>
                        </div>
                    </div>
                     <!-- Order Deadline -->
                    <div class="summary-section">
                        <label class="upload-label" for="order_deadline">Order Deadline <span style="color:red">*</span></label>
                        <input type="date" name="order_deadline" id="order_deadline" required
    style="margin-bottom:18px; font-size:1.15em; padding:10px 16px; border-radius:6px; border:1px solid #ccc; width:100%;">
                    </div>
                    <!-- Order Note -->
                    <div class="summary-section">
                        <label class="upload-label" for="order_note">Note</label>
                        <textarea name="order_note" id="order_note" rows="3" placeholder="Type any notes or requests here..." style="width:100%; border-radius:6px; border:1px solid #ccc; padding:8px; margin-bottom:18px;"></textarea>
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