<?php
session_start();
?>

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "decadhen");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$sql = "SELECT * FROM products WHERE ProductID = $product_id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// Fetch product sizes and prices
$attr_sql = "SELECT * FROM product_attributes WHERE ProductID = $product_id";
$attr_result = $conn->query($attr_sql);
$sizes = [];
while ($row = $attr_result->fetch_assoc()) {
    $sizes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details - Dhen's Kitchen</title>
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

    <!-- Item Detail -->
    <div class="item-detail-container">
        <div class="item-detail">
            <div class="item-detail-image">
                <img src="<?php echo htmlspecialchars($product['Image']); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
            </div>
            <div class="item-detail-info">
                <h1 class="item-detail-title"><?php echo htmlspecialchars($product['ProductName']); ?></h1>
                <div class="item-detail-description">
                    <?php echo htmlspecialchars($product['ProductDescription']); ?>
                </div>
                <div class="item-detail-specs">
                    <h3>Available Sizes & Prices</h3>
                    <ul>
                        <?php foreach ($sizes as $size): ?>
                            <li>
                                <span><?php echo htmlspecialchars($size['Size']); ?></span>
                                <span>₱<?php echo number_format($size['Price'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Allergens</h3>
                    <p><?php echo htmlspecialchars($product['ProductAllergens']); ?></p>
                </div>
                <div class="item-detail-actions">
                    <a href="order.php?id=<?php echo $product['ProductID']; ?>" class="item-detail-button order-button">Order Now</a>
                    <a href="menu.php" class="item-detail-button back-button">Back to Menu</a>
                </div>
            </div>
        </div>

        <!-- Related Items -->
        <div class="item-detail-related">
            <h2>You May Also Like</h2>
            <div class="related-items">
                <?php
                // Fetch other products except the current one
                $related_sql = "SELECT * FROM products WHERE ProductID != $product_id LIMIT 4";
                $related_result = $conn->query($related_sql);
                while ($related = $related_result->fetch_assoc()):
                ?>
                    <div class="menu-item" data-id="<?php echo htmlspecialchars($related['ProductID']); ?>">
                        <div class="item-color"></div>
                        <div class="item-image">
                            <img src="<?php echo htmlspecialchars($related['Image']); ?>" alt="<?php echo htmlspecialchars($related['ProductName']); ?>">
                        </div>
                        <div class="item-details">
                            <h3><?php echo htmlspecialchars($related['ProductName']); ?></h3>
                            <p><?php echo htmlspecialchars($related['ProductDescription']); ?></p>
                            <div class="item-button">
                                <a href="item.php?id=<?php echo htmlspecialchars($related['ProductID']); ?>">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
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
                <a href="delivery-policy.html">Delivery Policy</a> | <a href="privacy-policy.html">Privacy Policy</a> | <a href="terms.html">Terms & Conditions</a>
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