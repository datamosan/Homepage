<?php
session_start();
require_once "connection.php";
$page_title = "Sparkle up your day with goodness!"; // fallback

// Fetch announcement
$announcement = $page_title;
$res = sqlsrv_query($conn, "SELECT ContentDescription FROM decadhen.indexcontents WHERE ContentName='Announcement'");
if ($res && $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
    $announcement = $row['ContentDescription'];
}

// Fetch featured image
$featuredImage = 'images/dhens1.jpg'; // fallback
$res = sqlsrv_query($conn, "SELECT ContentDescription FROM decadhen.indexcontents WHERE ContentName='FeaturedImage'");
if ($res && $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
    $featuredImage = $row['ContentDescription'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dhen's Kitchen - Home</title>
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
                <?php if (isset($_SESSION['first_name'])): ?>
                    <div class="nav-dropdown">
                        <button class="nav-item nav-dropdown-btn">
                            Hi, <?php echo htmlspecialchars($_SESSION['first_name']); ?> <i class="fas fa-caret-down"></i>
                        </button>
                        <div class="nav-dropdown-content">
                            <?php if (isset($_SESSION['user_roles_id']) && $_SESSION['user_roles_id'] == 1): ?>
                                <a href="adminhub.php">Admin Hub</a>
                            <?php elseif (isset($_SESSION['user_roles_id']) && $_SESSION['user_roles_id'] == 2): ?>
                                <a href="profilepage.php">Profile</a>
                            <?php endif; ?>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="auth.php" class="nav-item">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <iframe src="carousel.php" width="101%" height="500" class="carouselContainer" scrolling="no"></iframe> 
        <div class="hero-content">
            <h1 class="hero-title">Dhen's Kitchen</h1>
            <h2 class="hero-subtitle">Authentic Filipino Cuisine</h2>
            <p>Experience the rich flavors of Filipino cooking with our homemade specialties.</p>
            <a href="order.php" class="cta-button">Order Now</a>
        </div>
    </section>
    
    <section class="carousel">
            
    </section>
    

    <!-- Features Section -->
    <section class="features">
        <div class="feature-box">
            <div class="feature-content">
                <h2 class="feature-title">Our Specialties</h2>
                <div class="feature-text">
                    <p>At Dhen's Kitchen, we take pride in our authentic Filipino recipes passed down through generations. Each dish is prepared with love and the finest ingredients.</p>
                    <p>From our famous Bicol Express to our mouthwatering desserts, we bring the taste of the Philippines to your table.</p>
                    <a href="menu.php" class="feature-button">Explore Our Menu</a>
                </div>
            </div>
            <div class="feature-image">
                <img src="<?php echo htmlspecialchars($featuredImage); ?>" alt="Dhen's Kitchen Specialty Dish">
            </div>
        </div>
    </section>

    <section class="instagram-grid">
        <h2 class="section-title">Recent Orders</h2>
        <behold-widget feed-id="zD9pzYpJzJUOEjav3w8w"></behold-widget>
        <script>
        (() => {
            const d=document,s=d.createElement("script");s.type="module";
            s.src="https://w.behold.so/widget.js";d.head.append(s);
        })();
        </script>
          </a>
        <div class="instagram-follow">
            <a href="https://instagram.com/dhenskitchen" class="instagram-follow-button" target="_blank">
                <i class="fab fa-instagram"></i> Follow us on Instagram
            </a>
        </div>
    </section>


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
