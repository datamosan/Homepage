<?php
session_start();
require_once "connection.php";
$page_title = "Sparkle up your day with goodness!"; // fallback
$res = $conn->query("SELECT ContentDescription FROM indexcontents WHERE ContentName='Announcement'");
if ($res && $row = $res->fetch_assoc()) {
    $announcement = $row['ContentDescription'];
}
$featuredImage = 'images/dhens1.jpg'; // fallback
$res = $conn->query("SELECT ContentDescription FROM indexcontents WHERE ContentName='FeaturedImage'");
if ($res && $row = $res->fetch_assoc()) {
    $featuredImage = $row['ContentDescription'];
}
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
<body data-logged-in="<?php echo isset($_SESSION['first_name']) ? '1' : ''; ?>">
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
                            <a href="profilepage.php">Profile</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="auth.php" class="nav-item">Login</a>
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
                    <div class="product-color"><i class="fas fa-image"></i></div>
                    <h3 class="product-title"><?php echo htmlspecialchars($product['ProductName']); ?></h3>
                    <p class="product-description"><?php echo htmlspecialchars($product['ProductDescription']); ?></p>
                    <?php
                        $pid = intval($product['ProductID']);
                        $attr = $conn->query("SELECT * FROM product_attributes WHERE ProductID = $pid");
                        $first = $attr->fetch_assoc();
                        $price = $first ? $first['Price'] : 0;
                    ?>
                    <div class="product-options">
                        <p class="product-price" id="product-price-<?php echo $product['ProductID']; ?>">₱<?php echo number_format($price, 2); ?></p>
                        <form method="post" action="add-to-cart.php" class="add-to-cart-form" data-product-id="<?php echo $product['ProductID']; ?>" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                            <input type="hidden" name="unit_price" value="<?php echo $price; ?>" class="unit-price-input">
                            <select name="size" class="size-select" data-id="<?php echo $product['ProductID']; ?>" style="margin-bottom:5px;">
                                <?php
                                $attr = $conn->query("SELECT * FROM product_attributes WHERE ProductID = $pid");
                                while ($row = $attr->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['Size']) . '" data-price="' . htmlspecialchars($row['Price']) . '">' . htmlspecialchars($row['Size']) . '</option>';
                                }
                                ?>
                            </select>
                            <button type="submit" class="add-to-cart" style="margin-top:5px;">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php
        // Display cart section for logged-in user
        $cart_items = [];
        $cart_total = 0;
        if (isset($_SESSION['user_id'])) {
            $user_id = intval($_SESSION['user_id']);
            $cart_id = $user_id; // CartID is always the same as UserID

            // Check for any cart (even completed)
            $cart_res = $conn->query("SELECT CartID FROM Cart WHERE UserID = $user_id ORDER BY CartID DESC LIMIT 1");
            if ($cart_row = $cart_res->fetch_assoc()) {
                $cart_id = $cart_row['CartID'];
                // Reactivate if not active
                $conn->query("UPDATE Cart SET Status = 'active' WHERE CartID = $cart_id");
            } else {
                // Create new cart if none exists
                $conn->query("INSERT INTO Cart (UserID, Status, CreatedDate) VALUES ($user_id, 'active', NOW())");
                $cart_id = $conn->insert_id;
            }

            $cart_items_query = $conn->query("
                SELECT ci.CartItemID, ci.ProductID, ci.CartQuantity, ci.UnitPrice, p.ProductName
                FROM CartItems ci
                JOIN products p ON ci.ProductID = p.ProductID
                WHERE ci.CartID = $cart_id
            ");
            while ($item = $cart_items_query->fetch_assoc()) {
                $item['Subtotal'] = $item['UnitPrice'] * $item['CartQuantity'];
                $cart_total += $item['Subtotal'];
                $cart_items[] = $item;
            }
        }
        ?>
        <!-- Cart Section -->
        <div class="cart-section">
            <h3 class="cart-title" style="text-align:center;">Your Cart</h3>
            <div id="cart-items" class="cart-items" style="display:flex; justify-content:center;">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <p class="empty-cart">Please <a href="auth.php">login</a> to view your cart.</p>
                <?php elseif (empty($cart_items)): ?>
                    <p class="empty-cart">Your cart is empty</p>
                <?php else: ?>
                    <table style="width:90%; max-width:600px; margin:auto; border-collapse:separate; border-spacing:0 8px; background:#fff;">
                        <thead>
                            <tr style="background:none;">
                                <th style="text-align:left; padding:8px 6px;">Product</th>
                                <th style="text-align:center; padding:8px 6px; width:80px;">Qty</th>
                                <th style="text-align:right; padding:8px 6px;">Unit Price</th>
                                <th style="text-align:right; padding:8px 6px;">Subtotal</th>
                                <th style="text-align:center; padding:8px 6px; width:40px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr style="background:none;">
                                <td style="padding:8px 6px; vertical-align:middle; text-align:left; font-weight:500; color:#234;">
                                    <?php echo htmlspecialchars($item['ProductName']); ?>
                                </td>
                                <td style="padding:8px 6px; text-align:center; vertical-align:middle;">
                                    <form class="update-cart-form" data-id="<?php echo $item['CartItemID']; ?>" style="display:inline;">
                                        <input type="hidden" name="cart_item_id" value="<?php echo $item['CartItemID']; ?>">
                                        <input type="number" name="quantity" value="<?php echo intval($item['CartQuantity']); ?>" min="1"
                                            style="width:48px; text-align:center; border-radius:6px; border:1px solid #ccc; padding:2px 4px;">
                                    </form>
                                </td>
                                <td style="padding:8px 6px; text-align:right; vertical-align:middle;">₱<?php echo number_format($item['UnitPrice'], 2); ?></td>
                                <td class="cart-subtotal" style="padding:8px 6px; text-align:right; vertical-align:middle; font-weight:500; color:var(--coral);">
                                    ₱<?php echo number_format($item['Subtotal'], 2); ?>
                                </td>
                                <td style="padding:8px 6px; text-align:center; vertical-align:middle;">
                                    <form class="remove-cart-form" data-id="<?php echo $item['CartItemID']; ?>" style="display:inline;">
                                        <button type="submit" title="Remove" style="background:none;border:none;color:red;cursor:pointer;font-size:1.2em;line-height:1;">&#10005;</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="cart-total" style="text-align:right; max-width:600px; margin:16px auto 0 auto; font-size:1.15em; font-weight:bold;">
                Total: <span id="cart-total-amount">₱<?php echo number_format($cart_total, 2); ?></span>
            </div>
            <div style="text-align:center; max-width:600px; margin:12px auto 0 auto;">
                <a href="place-order.php" id="checkout-button" class="checkout-button" style="min-width:180px;<?php echo (empty($cart_items) || !isset($_SESSION['user_id'])) ? 'pointer-events:none;opacity:0.5;' : ''; ?>">
                    Proceed to Checkout
                </a>
            </div>
        </div>

        <!-- Custom Order Section -->
        <div class="custom-order-section">
            <h3 class="custom-order-title">Need a Customized Cake?</h3>
            <p class="custom-order-description">We offer customized cakes for birthdays, weddings, and special occasions. Contact us to discuss your requirements and get a personalized quote.</p>
            <a href="contact.php?subject=custom-order" class="custom-order-button">Contact for Custom Order</a>
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