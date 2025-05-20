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
            <div class="page-title">Menu</div>
        </div>
        <nav class="main-nav">
            <div class="nav-links">
                <a href="index.html" class="nav-item">Home</a>
                <a href="about.html" class="nav-item">About Us</a>
                <a href="menu.html" class="nav-item active">Menu</a>
                <a href="order.html" class="nav-item">Order Now</a>
            </div>
            <a href="index.html" class="logo-container">
                <img src="logo.png" alt="Dhen's Kitchen Logo" class="logo-img">
            </a>
            <div class="nav-links">
                <a href="faq.html" class="nav-item">FAQs</a>
                <a href="contact.html" class="nav-item">Contact Us</a>
                <a href="auth.html" class="nav-item">Login/Register</a>
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
                // Optional: Add a default or dynamic description here if you want
                echo '  <div class="menu-grid">';
                // Fetch all products for this category
                $prod_query = "
                    SELECT p.*, pa.Price, pa.Size
                    FROM products p
                    LEFT JOIN product_attributes pa ON p.ProductID = pa.ProductID
                    WHERE p.ProductCategory = '" . mysqli_real_escape_string($conn, $category) . "'
                    ORDER BY p.ProductID, pa.Size
                ";
                $prod_result = mysqli_query($conn, $prod_query);
                $current_product = null;
                $last_product_id = null;
                while ($row = mysqli_fetch_assoc($prod_result)) {
                    if ($current_product !== $row['ProductID']) {
                        if ($current_product !== null) {
                            echo '      </ul>';
                            echo '      <div class="item-button"><a href="menu.php?ProductID=' . htmlspecialchars($last_product_id) . '">View Details</a></div>';
                            echo '  </div>';
                            echo '</div>';
                        }
                        echo '<div class="menu-item" data-id="' . htmlspecialchars($row['ProductID']) . '">';
                        echo '  <div class="item-color"></div>';
                        echo '  <div class="item-image">';
                        // Use a placeholder if Image is missing
                        $img = !empty($row['Image']) ? htmlspecialchars($row['Image']) : 'placeholder.jpg';
                        echo '      <img src="images/' . $img . '" alt="' . htmlspecialchars($row['ProductName']) . '">';
                        echo '  </div>';
                        echo '  <div class="item-details">';
                        echo '      <h3>' . htmlspecialchars($row['ProductName']) . '</h3>';
                        echo '      <p>' . htmlspecialchars($row['ProductDescription']) . '</p>';
                        echo '      <ul class="item-sizes">';
                        $current_product = $row['ProductID'];
                    }
                    // Show all sizes/prices for this product
                    if ($row['Price']) {
                        $size = !empty($row['Size']) ? htmlspecialchars($row['Size']) . ': ' : '';
                        echo '<li>' . $size . '₱' . number_format($row['Price'], 2) . '</li>';
                    }
                    $last_product_id = $row['ProductID'];
                }
                if ($current_product !== null) {
                    echo '      </ul>';
                    echo '      <div class="item-button"><a href="menu.php?ProductID=' . htmlspecialchars($last_product_id) . '">View Details</a></div>';
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

        <div class= container>
            <div class="container my-4" id="cont">
                <div class="row jumbotron">
                <?php
                if (isset($_GET['ProductID'])) {
                    $product_id = $_GET['ProductID'];
                    $query = "SELECT * FROM products WHERE ProductID = '$product_id'";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_assoc($result);
                    if ($row) {
                        $product_name = $row['ProductName'];
                        $description = $row['ProductDescription'];
                        $price = $row['Price'];
                        $image = $row['Image'];
                        $category = $row['ProductCategory'];
                        $query = "SELECT * FROM products WHERE ProductCategory = '$category' AND ProductID != '$product_id'";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="col-md-4">';
                                echo '<img src="images/' . $row['Image'] . '" alt="' . $row['ProductName'] . '" class="img-fluid">';
                                echo '</div>';
                                echo '<div class="col-md-8">';
                                echo '<h2>' . $row['ProductName'] . '</h2>';
                                echo '<p>' . $row['ProductDescription'] . '</p>';
                                echo '<p class="item-price">₱' . number_format($row['Price'], 2) . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo 'Error: ' . mysqli_error($conn);
                        }
                    } else {
                        echo '<p>Product not found.</p>';
                    }
                } else {
                    echo '<p>No product selected.</p>';
                }
                ?>
                </div>
            </div>

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