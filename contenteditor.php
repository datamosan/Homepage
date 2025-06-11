<?php
session_start();
require_once "connection.php";

if (!isset($_SESSION['user_roles_id']) || $_SESSION['user_roles_id'] != 1) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Hub - DecaDhen: Dhen's Kitchen</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --coral: #f48a8a;
            --mint: #8cd3a9;
            --teal: #1d4b53;
            --lavender: #b0b4ff;
            --white: #ffffff;
            --light-gray: #f5f5f5;
            --dark-gray: #333333;

            --shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
            --radius: 1.25rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-gray);
            color: var(--dark-gray);
            display: flex;
        }

        .sidebar {
            width: 240px;
            background-color: var(--teal);
            height: 100vh;
            padding: 2rem 1rem;
            color: var(--light-gray);
            position: fixed;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-shadow: var(--shadow);
        }

        .sidebar .logo {
            font-family: 'DM Serif Display', serif;
            font-size: 1.7rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            /* Reduce space below logo */
        }

        .sidebar .logo img {
            max-width: 150px;
            max-height: 150px;
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 50%;
            object-fit: contain;
        }

        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
            color: var(--light-gray);
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: background 0.3s;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar nav a i {
            color: var(--mint);
        }

        .main-content {
            margin-left: 240px;
            width: calc(100% - 240px);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: var(--teal);
            color: var(--light-gray);
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: var(--shadow);
        }

        .icons {
            display: flex;
            gap: 1.5rem;
        }

        .icons i {
            font-size: 1.3rem;
            cursor: pointer;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: var(--light-gray);
            font-size: 0.9rem;
            color: #555;
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            color: var(--teal);
            margin-bottom: 1rem;
            margin-top: 2rem;
            margin-left: 2rem;
        }

        .main-content>footer {
            margin-top: auto;
        }

        .admin-dropdown {
            position: relative;
            display: inline-block;
        }

        .admin-dropdown .fas.fa-user-circle {
            font-size: 1.4rem;
            cursor: pointer;
            color: var(--mint);
            transition: color 0.2s;
        }

        .admin-dropdown .fas.fa-user-circle:hover,
        .admin-dropdown .fas.fa-user-circle:focus {
            color: var(--coral);
        }

        .admin-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background: #fff;
            min-width: 180px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            border-radius: 0.5rem;
            z-index: 100;
            margin-top: 0.7rem;
            padding: 0.5rem 0;
        }

        .admin-dropdown-content a {
            color: var(--teal);
            padding: 0.7rem 1.2rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-weight: 500;
            border-radius: 0.3rem;
            transition: background 0.2s;
        }

        .admin-dropdown-content a:hover {
            background: var(--mint);
            color: #fff;
        }

        .content-edit-section {
            margin: 0 2rem 2rem 2rem;
            padding: 1.5rem;
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .content-edit-section h2 {
            font-family: 'DM Serif Display', serif;
            color: var(--teal);
            margin-bottom: 1.5rem;
        }

        .content-input {
            padding: 0.8rem;
            width: 100%;
            max-width: 1000px;
            border: 1px solid var(--light-gray);
            border-radius: 0.5rem;
            margin-right: 1rem;
            font-size: 1rem;
        }

        .content-save-btn {
            background-color: var(--teal);
            color: var(--white);
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }

        .content-save-btn:hover {
            background-color: var(--mint);
        }

        #pageTitleMsg {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: var(--coral);
        }

        .carousel-edit-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .carousel-edit-item {
            background: var(--light-gray);
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
        }

        .carousel-edit-item label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .feature-image img,
        .carousel-thumb {
            width: 220px;
            max-width: 215px;
            max-height: 160px;
            border-radius: 0.5rem;
            object-fit: cover;
            border: 1px solid #ccc;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="logo">
            <img src="logo.png" alt="Dhen's Kitchen Logo">
        </div>
        <nav>
            <a href="adminhub.php"><i class="fas fa-home"></i>Home</a>
            <a href="view-orders.php"><i class="fas fa-receipt"></i>Manage Orders</a>
            <a href="menu-management.php"><i class="fas fa-utensils"></i> Manage Menu</a>
            <a href="order-history.php"><i class="fas fa-box"></i>Orders History</a>
            <a href="customers.php"><i class="fas fa-address-card"></i>Customer Data</a>
            <a href="contenteditor.php" class="active"><i class="fas fa-pen"></i>Contents Editor</a>
        </nav>
    </aside>

    <div class="main-content">
        <header>
            <div class="icons">
                <i class="fas fa-bell"></i>
                <div class="admin-dropdown">
                    <i class="fas fa-user-circle" id="adminDropdownBtn" tabindex="0"></i>
                    <div class="admin-dropdown-content" id="adminDropdownMenu">
                        <a href="index.php"><i class="fas fa-file"></i> Check Website</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <h1> Contents Editor</h1>

        <div class="content-edit-section">
            <h2>Edit Announcement</h2>
            <form id="pageTitleForm">
                <?php
                $res = sqlsrv_query($conn, "SELECT ContentDescription FROM decadhen.indexcontents WHERE ContentName='Announcement'");
                $pageTitle = 'Sparkle up your day with goodness!';
                if ($res && $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                    $pageTitle = $row['ContentDescription'];
                }
                ?>
                <input type="text" id="pageTitleInput" value="<?php echo htmlspecialchars($pageTitle); ?>" class="content-input">
                <button type="submit" class="content-save-btn">Save</button>
                <span id="pageTitleMsg"></span>
            </form>
        </div>
        
        <div class="content-edit-section">
            <h2>Edit Carousel</h2>
            <form id="carouselForm" method="post" enctype="multipart/form-data" action="update-content.php">
                <div class="carousel-edit-list">
                    <?php
                    // Fetch carousel images from indexcontents
                    $carouselImages = [];
                    $res = sqlsrv_query($conn, "SELECT ContentName, ContentDescription FROM decadhen.indexcontents WHERE ContentName LIKE 'Carousel%' ORDER BY ContentName ASC");
                    if ($res) {
                        while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                            $carouselImages[] = $row;
                        }
                    }
                    // Show 3 slots by default
                    for ($i = 1; $i <= 3; $i++):
                        $name = "Carousel$i";
                        $img = '';
                        foreach ($carouselImages as $ci) {
                            if ($ci['ContentName'] === $name) $img = $ci['ContentDescription'];
                        }
                    ?>
                    <div class="carousel-edit-item">
                        <label>Image <?php echo $i; ?>:</label><br>
                        <?php if ($img): ?>
                            <img src="<?php echo htmlspecialchars($img); ?>" class="carousel-thumb" alt="Carousel <?php echo $i; ?>">
                        <?php else: ?>
                            <span style="color:#aaa;">No image</span>
                        <?php endif; ?>
                        <input type="file" name="carousel_<?php echo $i; ?>" accept="image/*">
                    </div>
                    <?php endfor; ?>
                </div>
                <button type="submit" class="content-save-btn" name="save_carousel">Save Carousel Images</button>
            </form>
        </div>
        
        <div class="content-edit-section">
            <h2>Edit Index Feautured Item</h2>
            <form method="post" enctype="multipart/form-data" action="update-content.php">
                <?php
                $featuredImage = 'images/dhens1.jpg';
                $res = sqlsrv_query($conn, "SELECT ContentDescription FROM decadhen.indexcontents WHERE ContentName='FeaturedImage'");
                if ($res && $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                    $featuredImage = $row['ContentDescription'];
                }
                ?>
                <img src="<?php echo htmlspecialchars($featuredImage); ?>" class="carousel-thumb" style="width:220px;max-width:100%;" alt="Featured Image"><br>
                <input type="file" name="featured_image" accept="image/*">
                <br><br><button type="submit" class="content-save-btn" name="save_featured_image">Save Featured Image</button>
            </form>
        </div>

        <footer>
            &copy; 2025 Dhen's Kitchen. All rights reserved.
        </footer>
    </div>

    <script>
        const adminBtn = document.getElementById('adminDropdownBtn');
        const adminMenu = document.getElementById('adminDropdownMenu');

        adminBtn.addEventListener('click', function(e) {
            adminMenu.style.display = adminMenu.style.display === 'block' ? 'none' : 'block';
            e.stopPropagation();
        });
        adminBtn.addEventListener('blur', function() {
            setTimeout(() => {
                adminMenu.style.display = 'none';
            }, 150);
        });
        document.addEventListener('click', function(e) {
            if (!adminBtn.contains(e.target)) {
                adminMenu.style.display = 'none';
            }
        });


        document.getElementById('pageTitleForm').onsubmit = function(e) {
            e.preventDefault();
            var input = document.getElementById('pageTitleInput');
            var msg = document.getElementById('pageTitleMsg');
            fetch('update-content.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'page_title=' + encodeURIComponent(input.value)
                })
                .then(res => res.json())
                .then(data => {
                    msg.textContent = data.success ? 'Saved!' : 'Failed to save.';
                    msg.style.color = data.success ? 'green' : 'red';
                })
                .catch(() => {
                    msg.textContent = 'Failed to save.';
                    msg.style.color = 'red';
                });
        };
    </script>

</body>

</html>