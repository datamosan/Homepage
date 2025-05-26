<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - Dhen's Kitchen</title>
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

    <!-- FAQ Section -->
    <section class="faq-section">
        <h1 class="faq-title">Frequently Asked Questions</h1>
        <div class="faq-container">
            <div class="faq-item active">
                <div class="faq-question">How do I place an order?</div>
                <div class="faq-answer">
                    <p>You can place an order in several ways:</p>
                    <ul>
                        <li>Order online through our website's "Order Now" page</li>
                        <li>Call us at (02) 8123-4567</li>
                        <li>Send us a message through our Contact page</li>
                        <li>Visit our physical store at 123 Filipino Street, Manila</li>
                    </ul>
                    <p>For large orders or catering services, we recommend contacting us at least 3 days in advance.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">What are your delivery areas and fees?</div>
                <div class="faq-answer">
                    <p>We deliver to the following areas:</p>
                    <ul>
                        <li>Manila - ₱100</li>
                        <li>Quezon City - ₱150</li>
                        <li>Makati - ₱150</li>
                        <li>Pasig - ₱200</li>
                        <li>Other Metro Manila areas - ₱200-300 (depending on distance)</li>
                    </ul>
                    <p>Free delivery for orders above ₱3,000 within Metro Manila.</p>
                    <p>For areas outside Metro Manila, please contact us for delivery options and fees.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">What payment methods do you accept?</div>
                <div class="faq-answer">
                    <p>We accept the following payment methods:</p>
                    <ul>
                        <li>Cash on Delivery (COD)</li>
                        <li>Bank Transfer (BDO, BPI, Metrobank)</li>
                        <li>GCash</li>
                        <li>PayMaya</li>
                        <li>Credit/Debit Cards (for in-store purchases)</li>
                    </ul>
                    <p>For online orders, payment must be completed before delivery.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">How far in advance should I order?</div>
                <div class="faq-answer">
                    <p>For regular menu items, we recommend ordering at least 24 hours in advance to ensure availability.</p>
                    <p>For cakes and desserts, please order 2-3 days in advance.</p>
                    <p>For customized cakes or catering services, please contact us at least 1 week in advance to discuss your requirements.</p>
                    <p>During peak seasons (holidays, Christmas, etc.), we recommend placing your orders even earlier.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">Do you cater to dietary restrictions?</div>
                <div class="faq-answer">
                    <p>Yes, we can accommodate certain dietary restrictions with advance notice. Please inform us of any allergies or dietary requirements when placing your order.</p>
                    <p>We offer:</p>
                    <ul>
                        <li>Vegetarian options</li>
                        <li>Low-sugar desserts (upon request)</li>
                        <li>Gluten-free options (limited menu)</li>
                    </ul>
                    <p>Note that our kitchen handles common allergens like nuts, dairy, and gluten, so cross-contamination is possible.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">Can I cancel or modify my order?</div>
                <div class="faq-answer">
                    <p>Order modifications or cancellations must be made at least 24 hours before the scheduled delivery or pickup time.</p>
                    <p>For special orders or customized items, cancellations may be subject to a cancellation fee depending on the preparation stage.</p>
                    <p>To modify or cancel an order, please contact us immediately at (02) 8123-4567 or email us at info@dhenskitchen.com.</p>
                </div>
            </div>
        </div>

        <div class="faq-contact">
            <p>Didn't find what you're looking for? Contact us directly!</p>
            <a href="contact.html" class="contact-button">Contact Us</a>
        </div>
    </section>

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