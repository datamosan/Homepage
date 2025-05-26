<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Dhen's Kitchen</title>
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

    <!-- Contact Header -->
    <div class="contact-header">
        <h1>Contact Us</h1>
    </div>

    <!-- Contact Main -->
    <div class="contact-main">
        <h2 class="contact-subtitle">Get in Touch</h2>

        <!-- Map Section -->
        <div class="map-container">
            <h3>Our Location</h3>
            <div class="map-placeholder">
                <i class="fas fa-map-marker-alt fa-3x"></i>
                <p>123 Filipino Street, Manila, Philippines</p>
                <p>Interactive map will be displayed here</p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-container">
            <h3 class="form-title">Send Us a Message</h3>
            <form id="contactForm" class="contact-form">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="general">General Inquiry</option>
                        <option value="order">Order Status</option>
                        <option value="feedback">Feedback</option>
                        <option value="catering">Custom Order/Catering</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Your Message *</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="submit-button">Send Message</button>
            </form>
        </div>

        <!-- Social Media Section -->
        <div class="contact-social">
            <h3>Connect With Us</h3>
            <div class="social-media-icons">
                <a href="#" class="social-media-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-media-icon"><i class="fab fa-instagram"></i></a>
            </div>
            <a href="#" class="messenger-chat"><i class="fab fa-facebook-messenger"></i> Chat with us on Messenger</a>
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