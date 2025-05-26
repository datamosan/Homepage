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

    <!-- Profile Content -->
    <main style="max-width: 500px; margin: 60px auto 40px; background: var(--white); box-shadow: var(--box-shadow); border-radius: 12px; padding: 40px 30px;">
        <h2 style="color: var(--teal); margin-bottom: 30px; text-align: center;">My Profile</h2>
        <div style="margin-bottom: 25px;">
            <label style="font-weight: bold; color: var(--coral); font-size: 1.1rem;">User Name:</label>
            <div style="color: var(--teal); font-size: 1.2rem; margin-top: 5px;">
                <?php echo htmlspecialchars($username); ?>
            </div>
        </div>
        <div>
            <label style="font-weight: bold; color: var(--coral); font-size: 1.1rem;">Address:</label>
            <div style="color: var(--teal); font-size: 1.1rem; margin-top: 5px;">
                <?php echo nl2br(htmlspecialchars($address)); ?>
            </div>
        </div>
    </main>

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
    <script src="script.js"></script>
</body>
</html>