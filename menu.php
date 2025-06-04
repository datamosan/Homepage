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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Dhen's Kitchen</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
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

    <!-- Menu Categories -->
    <section class="menu-categories">
        <?php
        // Fetch all unique categories from the products table
        $cat_query = "
            SELECT ProductCategory
            FROM decadhen.products
            GROUP BY ProductCategory
            ORDER BY MIN(ProductID)
        ";
        $cat_result = sqlsrv_query($conn, $cat_query);

        if ($cat_result && sqlsrv_has_rows($cat_result)) {
            echo '<div class="category-nav">';
            // Build the category navigation dynamically
            $cat_nav = [];
            $categories = [];
            while ($cat_row = sqlsrv_fetch_array($cat_result, SQLSRV_FETCH_ASSOC)) {
                $cat_id = strtolower(str_replace(' ', '-', $cat_row['ProductCategory']));
                $cat_name = htmlspecialchars($cat_row['ProductCategory']);
                $cat_nav[] = '<a href="#' . $cat_id . '" class="category-item">' . $cat_name . '</a>';
                $categories[] = $cat_row['ProductCategory'];
            }
            echo implode("\n", $cat_nav);
            echo '</div>';

            // Fetch categories again for sections
            foreach ($categories as $category) {
                $cat_id = strtolower(str_replace(' ', '-', $category));
                echo '<div id="' . $cat_id . '" class="menu-section">';
                echo '  <h2 class="category-title">' . htmlspecialchars($category) . '</h2>';
                echo '  <div class="menu-grid">';
                // Fetch all products for this category (parameterized)
                $prod_query = "SELECT * FROM decadhen.products WHERE ProductCategory = ? ORDER BY ProductID";
                $prod_result = sqlsrv_query($conn, $prod_query, [$category]);
                while ($row = sqlsrv_fetch_array($prod_result, SQLSRV_FETCH_ASSOC)) {
                    echo '<div class="menu-item" data-id="' . htmlspecialchars($row['ProductID']) . '">';
                    echo '  <div class="item-color"></div>';
                    echo '  <div class="item-image">';
                    $img = !empty($row['Image']) ? htmlspecialchars($row['Image']) : 'placeholder.jpg';
                    echo '      <img src="images/' . $img . '" alt="' . htmlspecialchars($row['ProductName']) . '">';
                    echo '  </div>';
                    echo '  <div class="item-details">';
                    echo '      <h3>' . htmlspecialchars($row['ProductName']) . '</h3>';
                    echo '      <div class="item-button"><a href="item.php?id=' . htmlspecialchars($row['ProductID']) . '">View Details</a></div>';
                    echo '  </div>';
                    echo '</div>';
                }
                echo '  </div>';
                echo '</div>';
            }
        } else {
            echo '<p>No categories found.</p>';
        }
        ?>
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