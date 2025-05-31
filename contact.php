<?php
session_start();
include 'connection.php'; // Include your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php'; // Load Composer's autoloader
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
            <div class="map-responsive" style="overflow:hidden; padding-bottom:56.25%; position:relative; height:0;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15443.83472976007!2d120.99835239989395!3d14.601429587988173!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9e733373ea3%3A0xed9f039f94a0752b!2sSanta%20Mesa%2C%20City%20Of%20Manila%2C%201016%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1748703953348!5m2!1sen!2sph"
                    width="100%" height="100%" style="border:0; position:absolute; top:0; left:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>

        <?php
        $name = $email = $phone = '';
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT UserName, UserEmail, UserPhone FROM users WHERE UserID = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($name, $email, $phone);
            $stmt->fetch();
            $stmt->close();
        }
        ?>

        <!-- Contact Form -->
        <div class="contact-form-container">
            <h3 class="form-title">Send Us a Message</h3>
            <?php if (isset($_SESSION['status'])): ?>
                <div class="alert alert-success text-center mx-auto" style="max-width:800px; font-size:1.1em; font-weight: bold; margin-bottom:20px; text-align: center;">
                    <?php echo htmlspecialchars($_SESSION['status']); ?>
                </div>
                <?php unset($_SESSION['status']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger text-center mx-auto" style="max-width:800px; font-size:1.1em; font-weight: bold; margin-bottom:20px; text-align: center;">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form id="contactForm" class="contact-form" action="contact-form-handler.php" method="POST">
                <div class="form-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($name); ?>"
                    <?php if ($name) echo 'readonly class="readonly-input"'; ?>>
                </div>
                <div class="form-group">
                    <label for="email">Your Email *</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>" <?php if ($email) echo 'readonly class="readonly-input"'; ?>>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" <?php if ($phone) echo 'readonly class="readonly-input"'; ?>>
                </div>
                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Order Status">Order Status</option>
                        <option value="Feedback">Feedback</option>
                        <option value="Custom Order/Catering">Custom Order/Catering</option>
                        <option value="Other">Other</option>
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

    <!-- <script src="script.js"></script> -->
</body>
</html>