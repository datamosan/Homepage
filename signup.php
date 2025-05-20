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
            <div class="page-title">Sign Up</div>
        </div>
        <nav class="main-nav">
            <div class="nav-links">
                <a href="index.php" class="nav-item">Home</a>
                <a href="about.html" class="nav-item">About Us</a>
                <a href="menu.html" class="nav-item">Menu</a>
                <a href="order.html" class="nav-item">Order Now</a>
            </div>
            <a href="index.html" class="logo-container">
                <img src="logo.png" alt="Dhen's Kitchen Logo" class="logo-img">
            </a>
            <div class="nav-links">
                <a href="faq.html" class="nav-item">FAQs</a>
                <a href="contact.html" class="nav-item">Contact Us</a>
                <a href="auth.html" class="nav-item active">Login/Register</a>
            </div>
        </nav>
    </header>

    <!-- Sign Up Main -->
    <div class="auth-main">
        <h2 class="auth-subtitle">Create an Account</h2>
        <div class="auth-form-container">
            <form id="signupForm" class="auth-form" action="reg.php" method="post">
                <input type="text" id="fullName" name="fullName" placeholder="Full Name" required>
                <input type="text" id="address" name="address" placeholder="Address" required>
                <input type="email" id="email" name="email" placeholder="Email Address" required>
                <input type="tel" id="phone" name="phone" maxlength="10" placeholder="Phone Number" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                <div class="terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">I agree to the <a href="terms.html">Terms & Conditions</a> and <a href="privacy-policy.html">Privacy Policy</a></label>
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
            <div class="footer-hours">
                <h3>Opening Hours</h3>
                <p>Monday - Friday: 10:00 AM - 9:00 PM</p>
                <p>Saturday - Sunday: 9:00 AM - 10:00 PM</p>
                <p>Holidays: 10:00 AM - 8:00 PM</p>
            </div>
            <div class="footer-social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                </div>
                <div class="footer-links">
                    <p><a href="delivery-policy.html">Delivery Policy</a> | <a href="privacy-policy.html">Privacy Policy</a> | <a href="terms.html">Terms & Conditions</a></p>
                </div>
            </div>
        </div>
        <div class="footer-nav">
            <div class="footer-menu">
                <a href="menu.html">
                    <div class="menu-icon"></div>
                    <div class="menu-icon"></div>
                    <div class="menu-icon"></div>
                    <span>Menu</span>
                </a>
            </div>
            <div class="footer-home">
                <a href="index.html">
                    <div class="home-icon"></div>
                    <div class="home-icon"></div>
                    <div class="home-icon"></div>
                    <span>Home</span>
                </a>
            </div>
            <div class="footer-account">
                <a href="auth.html">
                    <div class="account-icon"></div>
                    <div class="account-icon"></div>
                    <div class="account-icon"></div>
                    <span>Account</span>
                </a>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2023 Dhen's Kitchen. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>