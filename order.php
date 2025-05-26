<?php
session_start();
?>

<?php
// filepath: c:\xampp\htdocs\decadhen\Homepage\order.php
$conn = new mysqli("localhost", "root", "", "decadhen");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Now - Dhen's Kitchen</title>
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

    <!-- Order Header -->
    <div class="order-header">
        <h1>Order Online</h1>
    </div>

    <!-- Order Main -->
    <div class="order-main">
        <h2 class="order-subtitle">Select Your Items</h2>

        <!-- Products Grid -->
        <div class="products-grid">
            <?php while ($product = $products->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-color"></div>
                    <h3 class="product-title"><?php echo htmlspecialchars($product['ProductName']); ?></h3>
                    <p class="product-description"><?php echo htmlspecialchars($product['ProductDescription']); ?></p>
                    <?php
                        // Fetch sizes/prices for this product
                        $pid = intval($product['ProductID']);
                        $attr = $conn->query("SELECT * FROM product_attributes WHERE ProductID = $pid");
                        $first = $attr->fetch_assoc();
                        $price = $first ? $first['Price'] : 0;
                    ?>
                    <div class="product-options">
                        <p class="product-price" id="product-price-<?php echo $product['ProductID']; ?>">₱<?php echo number_format($price, 2); ?></p>
                        <select class="size-select" data-id="<?php echo $product['ProductID']; ?>">
                            <?php
                            $attr = $conn->query("SELECT * FROM product_attributes WHERE ProductID = $pid");
                            while ($row = $attr->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($row['Size']) . '" data-price="' . htmlspecialchars($row['Price']) . '">'
                                    . htmlspecialchars($row['Size']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button class="add-to-cart"
                        data-id="<?php echo $product['ProductID']; ?>"
                        data-name="<?php echo htmlspecialchars($product['ProductName']); ?>"
                        data-price="<?php echo $price; ?>">
                        Add to Cart
                    </button>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Cart Section -->
        <div class="cart-section">
            <h3 class="cart-title">Your Cart</h3>
            <div id="cart-items" class="cart-items">
                <p class="empty-cart">Your cart is empty</p>
            </div>
            <div class="cart-total">
                Total: <span id="cart-total-amount">₱0.00</span>
            </div>
            <button id="checkout-button" class="checkout-button">Proceed to Checkout</button>
        </div>

        <!-- Custom Order Section -->
        <div class="custom-order-section">
            <h3 class="custom-order-title">Need a Customized Cake?</h3>
            <p class="custom-order-description">We offer customized cakes for birthdays, weddings, and special occasions. Contact us to discuss your requirements and get a personalized quote.</p>
            <a href="contact.html?subject=custom-order" class="custom-order-button">Contact for Custom Order</a>
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