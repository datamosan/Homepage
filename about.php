<?php
session_start();
require_once "connection.php";
$page_title = "Sparkle up your day with goodness!"; // fallback

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
    <title>About Us - Dhen's Kitchen</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .about-hero-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            margin-top: 40px;
            color: var(--teal);
        }

        h2 {
            font-size: 1.5rem;
            color: var(--teal);
            margin-bottom: 0.5rem;
        }

        .space {
            height: 1rem;
        }

        p {
            font-size: 0.9rem;
        }
        .content-section {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 3rem;
            margin: 0 0 5rem 0;
            flex-wrap: wrap;
        }

        .content-section.reverse {
            flex-direction: row-reverse;
        }

        .content-image {
            width: 550px;
            height: 404.25px;
            background: #e5e5e5;
            border-radius: 0.5rem;
            object-fit: cover;
            flex-shrink: 0;
        }

        .content-details {
            max-width: 550px;
            flex: 1;
        }

        .content-details::before {
            content: "";
            display: block;
            width: 30%;
            height: 3px;
            background-color: var(--lavender);
            margin-bottom: 10px;

        }

        .content-label {
            display: block;
            width: 180px;
            height: 16px;
            background: red;
            margin-bottom: 1.2rem;
        }

        .content-lines {
            margin-top: 1.2rem;
        }

        .content-line {
            width: 100%;
            height: 7px;
            background: red;
            margin-bottom: 0.7rem;
            border-radius: 2px;
        }
    </style>
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

    <!-- About Hero Section -->
    <section>
        <!-- <h1 class="about-title">About Dhen's Kitchen</h1>
        <p class="about-subtitle">Authentic Filipino Cuisine Since 2015</p> -->
        <image src="images/family.jpg" alt="Dhen's Kitchen Family" class="about-hero-image">
            <h1>"Lorem ipsum dolor sit amet, consectetur adipiscing elit"</h1>
    </section>

    <!-- About Content Section -->
    <section class="about-content">
        <div class="content-section">
            <img src="images/sister1.jpg" alt="Family" class="content-image">
            <div class="content-details">
                <h2>Name of a Person in the Company</h2>
                <p> Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.</p>
                <div class="space"></div>
                <p> Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.</p>
            </div>
        </div>

        <div class="content-section reverse">
            <img src="images/sister2.webp" alt="Family" class="content-image">
            <div class="content-details">
                <h2>Name of a Person in the Company</h2>
                <p> Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.</p>
                <div class="space"></div>
                <p> Lorem ipsum dolor sit amet consectetur adipiscing elit. Quisque faucibus ex sapien vitae pellentesque sem placerat. In id cursus mi pretium tellus duis convallis. Tempus leo eu aenean sed diam urna tempor. Pulvinar vivamus fringilla lacus nec metus bibendum egestas. Iaculis massa nisl malesuada lacinia integer nunc posuere. Ut hendrerit semper vel class aptent taciti sociosqu. Ad litora torquent per conubia nostra inceptos himenaeos.</p>
            </div>
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