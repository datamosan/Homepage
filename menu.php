<?php
session_start();
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

    <?php include 'connection.php';?>

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

    <!-- Menu Categories -->
    <section class="menu-categories">
        <?php
        // Fetch all unique categories from the products table
        $cat_query = "SELECT DISTINCT ProductCategory FROM products";
        $cat_result = mysqli_query($conn, $cat_query);

        if ($cat_result && mysqli_num_rows($cat_result) > 0) {
            echo '<div class="category-nav">';
            // Build the category navigation dynamically
            $cat_nav = [];
            while ($cat_row = mysqli_fetch_assoc($cat_result)) {
                $cat_id = strtolower(str_replace(' ', '-', $cat_row['ProductCategory']));
                $cat_name = htmlspecialchars($cat_row['ProductCategory']);
                $cat_nav[] = '<a href="#' . $cat_id . '" class="category-item">' . $cat_name . '</a>';
            }
            echo implode("\n", $cat_nav);
            echo '</div>';

            // Reset pointer and fetch categories again for sections
            mysqli_data_seek($cat_result, 0);
            while ($cat_row = mysqli_fetch_assoc($cat_result)) {
                $category = $cat_row['ProductCategory'];
                $cat_id = strtolower(str_replace(' ', '-', $category));
                echo '<div id="' . $cat_id . '" class="menu-section">';
                echo '  <h2 class="category-title">' . htmlspecialchars($category) . '</h2>';
                echo '  <div class="menu-grid">';
                // Fetch all products for this category
                $prod_query = "
                    SELECT * FROM products
                    WHERE ProductCategory = '" . mysqli_real_escape_string($conn, $category) . "'
                    ORDER BY ProductID
                ";
                $prod_result = mysqli_query($conn, $prod_query);
                while ($row = mysqli_fetch_assoc($prod_result)) {
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

        <!-- Pagination -->
        <div class="pagination">
            <a href="#" class="page-prev">Previous</a>
            <div class="page-number">1/3</div>
            <a href="#" class="page-next">Next</a>
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