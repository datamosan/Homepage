<?php
session_start();
require_once "connection.php";
$page_title = "Sparkle up your day with goodness!"; // fallback

$announcement = $page_title;
$res = sqlsrv_query($conn, "SELECT ContentDescription FROM decadhen.indexcontents WHERE ContentName='Announcement'");
if ($res && $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
    $announcement = $row['ContentDescription'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - Dhen's Kitchen</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-bar">
            <div class="page-title"><?php echo htmlspecialchars($announcement); ?></div>
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
                <a href="auth.php" class="nav-item">Login</a>
            </div>
        </nav>
    </header>

    <!-- Auth Main -->
    <div class="auth-main">
        <div class="auth-container">
            <div class="logo-large">
                <img src="logo.png" alt="Dhen's Kitchen Logo">
            </div>
            <div class="welcome-message">
                <h2>Welcome to Dhen's Kitchen</h2>
                <p>Sign in to your account to order your favorite Filipino dishes, track your orders, and more.</p>
            </div>
            <div class="auth-buttons">
                <a href="login.php" class="auth-button">Login</a>
                <a href="signup.php" class="auth-button">Create an Account</a>
            </div>
            <div class="guest-option">
                <p>Just browsing? You can continue as a guest.</p>
                <a href="index.php" class="guest-button">Continue as Guest</a>
            </div>
        </div>
    </div>

<!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-info">
                <h3>Dhen's Kitchen</h3>
                <p>Sta. Mesa, Manila, Philippines 1016</p>
                <p>Phone: 0949 348 2110 | 0915 007 7783</p>
                <p>Email: dhenskitchen@gmail.com</p>
            </div>
            <div class="copyright">
            <div class="footer-links">
                <br>
                <a href="delivery-policy.php">Delivery Policy</a> | 
                <a href="privacy-policy.php">Privacy Policy</a> | 
                <a href="terms.php">Terms & Conditions</a>
            </div>
            <p>&copy; 2025 Dhen's Kitchen. All rights reserved.</p>
            </div>
            <div class="footer-social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="https://www.facebook.com/dhenskitchen" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/dhenskitchen" class="social-icon"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>