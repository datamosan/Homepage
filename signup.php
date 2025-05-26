<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Dhen's Kitchen</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <a href="auth.html" class="nav-item">Login</a>
            </div>
        </nav>
    </header>

    <!-- Sign Up Main -->
    <div class="auth-main">
        <h2 class="auth-subtitle">Create an Account</h2>
        <br>
        <div class="auth-flex-row">
            <div class="auth-form-container">
                <form id="signupForm" class="auth-form" action="reg.php" method="post">
                    <label for="fullName">Name:</label>
                    <input type="text" id="fullName" name="fullName" placeholder="Full Name" required>

                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" placeholder="Address" required>

                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" placeholder="Email Address" required>

                    <label for="phone">Mobile Number:</label>
                    <div class="phone-group">
                        <span class="country-code">+63</span>
                        <input type="tel" id="phone" name="phone" maxlength="10" placeholder="Mobile Number" required>
                    </div>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>

                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Password" required>

                    <div class="terms">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="terms.php">Terms & Conditions</a> and <a href="privacy-policy.php">Privacy Policy</a></label>
                    </div>
                    <div class="newsletter">
                        <input type="checkbox" id="newsletter" name="newsletter">
                        <label for="newsletter">Subscribe to our newsletter</label>
                    </div>
                    <button type="submit" name="signUp" class="auth-button">Create Account</button>
                </form>
                <div class="auth-redirect">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
            <div class="auth-benefits">
                <h3>Benefits of Creating an Account</h3>
                <ul>
                    <li>Track your orders in real-time</li>
                    <li>Save your favorite items for quick reordering</li>
                    <li>Receive exclusive offers and promotions</li>
                    <li>Faster checkout process</li>
                    <li>Access to order history</li>
                </ul>
            </div>
        </div>
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

    <script src="script.js"></script>
</body>
</html>