<?php
session_start();
require_once "connection.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.html");
    exit();
}

// Fetch user info from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT UserName, UserAddress FROM users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $address);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Dhen's Kitchen</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-bar">
            <div class="page-title">Profile</div>
        </div>
        <nav class="main-nav">
            <div class="nav-links">
                <a href="about.php" class="nav-item">About Us</a>
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

    <!-- Profile Info Box -->
    <div class="profile-box">
        <h1><?php echo htmlspecialchars($username); ?></h1>
        <hr class="profile-divider">
        <div class="profile-address-row" id="addressRow">
            <span class="profile-address-label">Address:</span>
            <span class="profile-address-text" id="addressText"><?php echo htmlspecialchars($address); ?></span>
            <button class="profile-edit-btn" id="editAddressBtn" type="button"><i class="fas fa-edit"></i> Edit</button>
            <form id="addressForm" style="display:none; margin:0;">
                <textarea class="profile-address-input" id="addressInput" rows="2"><?php echo htmlspecialchars($address); ?></textarea>
                <button class="profile-save-btn" type="submit">Save</button>
                <button class="profile-cancel-btn" type="button" id="cancelEditBtn">Cancel</button>
                <span class="profile-address-msg" id="addressMsg"></span>
            </form>
        </div>
        <h2 class="orders-title">My Orders</h2>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Proof of Payment</th>
                    <th>Status</th>
                    <th>Deadline</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $ordersql = "SELECT OrderDate, PaymentProof, OrderStatus, OrderDeadline FROM orders WHERE UserID = ? ORDER BY OrderDate DESC";
            $orderstmt = $conn->prepare($ordersql);
            $orderstmt->bind_param("i", $user_id);
            $orderstmt->execute();
            $orderstmt->bind_result($odate, $proof, $ostatus, $odeadline);
            $hasOrders = false;
            while ($orderstmt->fetch()):
                $hasOrders = true;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($odate); ?></td>
                    <td>
                        <?php if ($proof): ?>
                            <a href="<?php echo htmlspecialchars($proof); ?>" target="_blank" class="order-proof-link">Show Image</a>
                        <?php else: ?>
                            <span class="order-no-proof">No Proof</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower($ostatus); ?>">
                            <?php echo ucfirst($ostatus); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($odeadline); ?></td>
                </tr>
            <?php endwhile; $orderstmt->close(); ?>
            <?php if (!$hasOrders): ?>
                <tr><td colspan="4" class="orders-none">No orders found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-info">
                <h3>Dhen's Kitchen</h3>
                <p>123 Filipino Street</p>
                <p>Manila, Philippines</p>
                <p>Phone: (02) 8123-4567</p>
                <p>Email: info@dhenskitchen.com</p>
            </div>
            <div class="copyright">
                <div class="footer-links">
                    <br>
                    <a href="delivery-policy.php">Delivery Policy</a> | <a href="privacy-policy.php">Privacy Policy</a> | <a href="terms.php">Terms & Conditions</a>
                </div>
                <p>&copy; 2023 Dhen's Kitchen. All rights reserved.</p>
            </div>
            <div class="footer-social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="https://web.facebook.com/dhenskitchen?mibextid=wwXIfr&rdid=4NGrYasRkC4yQ3iE&share_url=https%3A%2F%2Fweb.facebook.com%2Fshare%2F1BdBoZHYRb%2F%3Fmibextid%3DwwXIfr%26_rdc%3D1%26_rdr#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/dhenskitchen/?igsh=azgxNndtd2E0ZHN1#" class="social-icon"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
    // Address edit logic
    const editBtn = document.getElementById('editAddressBtn');
    const addressText = document.getElementById('addressText');
    const addressForm = document.getElementById('addressForm');
    const addressInput = document.getElementById('addressInput');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const addressMsg = document.getElementById('addressMsg');

    editBtn.onclick = function() {
        addressText.style.display = 'none';
        editBtn.style.display = 'none';
        addressForm.style.display = 'flex';
        addressInput.value = addressText.textContent;
        addressMsg.textContent = '';
    };
    cancelEditBtn.onclick = function() {
        addressForm.style.display = 'none';
        addressText.style.display = '';
        editBtn.style.display = '';
        addressMsg.textContent = '';
    };
    addressForm.onsubmit = function(e) {
        e.preventDefault();
        fetch('update-address.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'address=' + encodeURIComponent(addressInput.value)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                addressText.textContent = addressInput.value;
                addressMsg.textContent = 'Address updated!';
                addressMsg.classList.remove('error');
                setTimeout(() => {
                    addressForm.style.display = 'none';
                    addressText.style.display = '';
                    editBtn.style.display = '';
                    addressMsg.textContent = '';
                }, 1200);
            } else {
                addressMsg.textContent = 'Update failed.';
                addressMsg.classList.add('error');
            }
        })
        .catch(() => {
            addressMsg.textContent = 'Update failed.';
            addressMsg.classList.add('error');
        });
    };
    </script>
    <script src="script.js"></script>
</body>
</html>