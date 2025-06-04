<?php
session_start();
require_once 'connection.php';

// Handle Add New Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
  $category = $_POST['category'];
  $item = $_POST['item'];
  $variation = $_POST['variation'];
  $price = floatval($_POST['price']);
  $description = $_POST['description'] ?? '';

  // Handle image upload
  $image_filename = '';
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_filename = uniqid('menu_', true) . '.' . $ext;
    move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $image_filename);
  }

  // Check if product exists
  $product_check = sqlsrv_query($conn, "SELECT ProductID FROM decadhen.products WHERE ProductName=? AND ProductCategory=?", [$item, $category]);
  $product_row = sqlsrv_fetch_array($product_check, SQLSRV_FETCH_ASSOC);

  if ($product_row) {
    $product_id = $product_row['ProductID'];
    // Update image and description if new image uploaded
    if ($image_filename) {
      sqlsrv_query($conn, "UPDATE decadhen.products SET Image=?, ProductDescription=? WHERE ProductID=?", [$image_filename, $description, $product_id]);
    } else {
      sqlsrv_query($conn, "UPDATE decadhen.products SET ProductDescription=? WHERE ProductID=?", [$description, $product_id]);
    }
  } else {
    $insert_product = sqlsrv_query($conn, "INSERT INTO decadhen.products (ProductName, ProductCategory, Image, ProductDescription) OUTPUT INSERTED.ProductID VALUES (?, ?, ?, ?)", [$item, $category, $image_filename, $description]);
    $inserted = sqlsrv_fetch_array($insert_product, SQLSRV_FETCH_ASSOC);
    $product_id = $inserted['ProductID'];
  }

  // Insert into product_attributes
  if ($variation || $price) {
    sqlsrv_query($conn, "INSERT INTO decadhen.product_attributes (ProductID, Size, Price) VALUES (?, ?, ?)", [$product_id, $variation, $price]);
  }

  header("Location: menu-management.php?success=1");
  exit;
}

// Handle Edit Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_item'])) {
  $edit_id = intval($_POST['edit_id']);
  $category = $_POST['edit_category'];
  $item = $_POST['edit_item'];
  $variation = $_POST['edit_variation'];
  $price = floatval($_POST['edit_price']);
  $edit_description = $_POST['edit_description'] ?? '';

  // Handle image upload
  if (isset($_FILES['edit_image']) && $_FILES['edit_image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['edit_image']['name'], PATHINFO_EXTENSION);
    $image_filename = uniqid('menu_', true) . '.' . $ext;
    move_uploaded_file($_FILES['edit_image']['tmp_name'], 'images/' . $image_filename);
    sqlsrv_query($conn, "UPDATE decadhen.products SET ProductName=?, ProductCategory=?, Image=?, ProductDescription=? WHERE ProductID=?", [$item, $category, $image_filename, $edit_description, $edit_id]);
  } else {
    sqlsrv_query($conn, "UPDATE decadhen.products SET ProductName=?, ProductCategory=?, ProductDescription=? WHERE ProductID=?", [$item, $category, $edit_description, $edit_id]);
  }

  // Update product_attributes (assumes one attribute per product for simplicity)
  $attr_check = sqlsrv_query($conn, "SELECT SizeID FROM decadhen.product_attributes WHERE ProductID=?", [$edit_id]);
  $attr_row = sqlsrv_fetch_array($attr_check, SQLSRV_FETCH_ASSOC);
  if ($attr_row) {
    $size_id = $attr_row['SizeID'];
    sqlsrv_query($conn, "UPDATE decadhen.product_attributes SET Size=?, Price=? WHERE SizeID=?", [$variation, $price, $size_id]);
  } else {
    sqlsrv_query($conn, "INSERT INTO decadhen.product_attributes (ProductID, Size, Price) VALUES (?, ?, ?)", [$edit_id, $variation, $price]);
  }

  header("Location: menu-management.php?edited=1");
  exit;
}

// Handle Delete Item
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
  $delete_id = intval($_GET['delete']);
  sqlsrv_query($conn, "DELETE FROM decadhen.product_attributes WHERE ProductID=?", [$delete_id]);
  sqlsrv_query($conn, "DELETE FROM decadhen.products WHERE ProductID=?", [$delete_id]);
  header("Location: menu-management.php?deleted=1");
  exit;
}

// Fetch all menu items
$query = "
    SELECT p.ProductID, p.ProductCategory, p.ProductName, pa.SizeID, pa.Size, pa.Price, p.Image
    FROM decadhen.products p
    LEFT JOIN decadhen.product_attributes pa ON p.ProductID = pa.ProductID
    ORDER BY p.ProductCategory, p.ProductName, pa.Size
";
$result = sqlsrv_query($conn, $query);

// For Edit Modal
$edit_row = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
  $edit_id = intval($_GET['edit']);
  $edit_query = "
        SELECT p.ProductID, p.ProductCategory, p.ProductName, pa.SizeID, pa.Size, pa.Price, p.Image, p.ProductDescription
        FROM decadhen.products p
        LEFT JOIN decadhen.product_attributes pa ON p.ProductID = pa.ProductID
        WHERE p.ProductID = ?
    ";
  $edit_result = sqlsrv_query($conn, $edit_query, [$edit_id]);
  $edit_row = sqlsrv_fetch_array($edit_result, SQLSRV_FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menu Management - DecaDhen Admin</title>
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
      padding: 0;
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

    h1 {
      font-family: 'DM Serif Display', serif;
      color: var(--teal);
      margin-bottom: 1rem;
      margin-top: 2rem;
      margin-left: 2rem;
    }

    .controls,
    .filter-bar {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
      flex-wrap: wrap;
      margin-left: 2rem;
      margin-right: 2rem;
    }

    .controls input[type="text"],
    .filter-bar input[type="text"],
    .filter-bar select {
      flex: 1;
      padding: 0.5rem 1rem;
      border-radius: var(--radius);
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .controls button {
      background-color: var(--teal);
      color: var(--white);
      border: none;
      padding: 0.5rem 1rem;
      border-radius: var(--radius);
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .controls button:hover {
      background-color: var(--coral);
    }

    table {
      width: calc(100% - 4rem);
      margin-left: 2rem;
      margin-right: 2rem;
      border-collapse: collapse;
      background-color: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    thead {
      background-color: var(--teal);
      color: var(--white);
    }

    th,
    td {
      padding: 1rem 1.2rem;
      text-align: left;
      border-bottom: 1px solid #ddd;
      cursor: default;
    }

    th.sortable {
      cursor: pointer;
      user-select: none;
    }

    th.sortable:hover {
      background-color: rgba(255, 255, 255, 0.15);
    }

    th.sortable.active {
      background-color: var(--mint) !important;
      color: var(--teal);
    }

    th.sortable.active i {
      color: var(--teal);
    }

    tbody tr:hover {
      background-color: rgba(255, 255, 255, 0.3);
    }

    .pagination {
      margin-top: 1rem;
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .pagination button {
      border: none;
      background-color: var(--teal);
      color: var(--white);
      padding: 0.5rem 0.9rem;
      border-radius: 0.5rem;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .pagination button:disabled {
      background-color: #999;
      cursor: default;
    }

    .pagination button.active {
      background-color: var(--coral);
    }

    footer {
      text-align: center;
      padding: 1rem;
      background-color: var(--light-gray);
      font-size: 0.9rem;
      color: #555;
    }

    .main-content>footer,
    .main>footer {
      margin-top: auto;
    }

    /* Custom styles for menu images and actions */
    td img.menu-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 0.5rem;
      margin-right: 0.5rem;
      vertical-align: middle;
    }

    .actions a,
    .actions button {
      margin-right: 0.5rem;
      border: none;
      padding: 0.4rem 0.8rem;
      border-radius: 0.5rem;
      font-weight: 600;
      cursor: pointer;
      background: none;
      color: var(--teal);
      text-decoration: none;
      font-size: 1rem;
      transition: color 0.2s;
      box-shadow: none;
      outline: none;
      display: inline;
    }

    .actions .edit-btn:hover {
      color: var(--coral);
    }

    .actions .delete-btn {
      color: var(--coral);
    }

    .actions .delete-btn:hover {
      color: #a94442;
    }

    .add-form {
      margin-top: 2rem;
      background: var(--white);
      padding: 1.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      margin-left: 2rem;
      margin-right: 2rem;
    }

    .add-form input,
    .add-form select {
      width: 100%;
      padding: 0.6rem 1rem;
      margin-bottom: 1rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
    }

    .add-form button {
      background: var(--teal);
      color: white;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 0.5rem;
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
    }

    .add-form button:hover {
      background: var(--coral);
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal-content {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      width: 90%;
      max-width: 400px;
      box-shadow: var(--shadow);
    }

    .modal-content input,
    .modal-content select {
      width: 100%;
      padding: 0.6rem 1rem;
      margin-bottom: 1rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
    }

    .modal-content button {
      background: var(--teal);
      color: white;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 0.5rem;
      font-weight: 600;
      cursor: pointer;
    }

    .modal-content button:hover {
      background: var(--coral);
    }

    /* Toast */
    #toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--teal);
      color: white;
      padding: 1rem 1.5rem;
      border-radius: var(--radius);
      opacity: 0;
      transition: opacity 0.5s;
      box-shadow: var(--shadow);
      z-index: 2000;
    }

    /* Style for description textarea in modal and add form */
    textarea[name="edit_description"],
    textarea[name="description"] {
      width: 100%;
      min-height: 60px;
      max-height: 180px;
      padding: 0.6rem 1rem;
      margin-bottom: 1rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      resize: vertical;
      box-sizing: border-box;
      background: #fafbfc;
      transition: border-color 0.2s;
    }

    textarea[name="edit_description"]:focus,
    textarea[name="description"]:focus {
      border-color: var(--teal);
      outline: none;
      background: #fff;
    }

    /* Make textarea placeholder font match input placeholder font */
    textarea::placeholder,
    input::placeholder {
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      color: #888;
      opacity: 1;
    }

    .add-form select,
    .modal-content select {
      width: 100%;
      padding: 0.6rem 1rem;
      margin-bottom: 1rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      background: #fafbfc;
      transition: border-color 0.2s;
    }

    .add-form select:focus,
    .modal-content select:focus {
      border-color: var(--teal);
      outline: none;
      background: #fff;
    }

    .add-form input[type="file"],
    .modal-content input[type="file"] {
      padding: 0.4rem 0.5rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
      background: #fafbfc;
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      margin-bottom: 1rem;
    }

    .add-form input[type="file"]:focus,
    .modal-content input[type="file"]:focus {
      border-color: var(--teal);
      outline: none;
      background: #fff;
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
      <a href="menu-management.php" class="active"><i class="fas fa-utensils"></i> Manage Menu</a>
      <a href="order-history.php"><i class="fas fa-box"></i>Orders History</a>
      <a href="customers.php"><i class="fas fa-address-card"></i>Customer Data</a>
      <a href="contenteditor.php"><i class="fas fa-pen"></i>Contents Editor</a>
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

    <div class="content">
      <h1>Menu Management</h1>

      <!-- Filter/Search -->
      <div class="filter-bar">
        <input type="text" placeholder="Search item..." id="searchInput" onkeyup="filterMenu()">
        <select id="categoryFilter" onchange="filterMenu()">
          <option value="">All Categories</option>
          <option>Featured</option>
          <option>Pinoy Pride</option>
          <option>Special Cakes</option>
          <option>Bicol Specialties</option>
          <option>Pasta and Noodles</option>
        </select>
      </div>

      <!-- Menu Table -->
      <table id="menuTable">
        <thead>
          <tr>
            <th>Category</th>
            <th>Item</th>
            <th>Variation</th>
            <th>Price</th>
            <th>Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)): ?>
            <?php
            $img = !empty($row['Image']) ? htmlspecialchars($row['Image']) : 'placeholder.jpg';
            $edit_link = 'menu-management.php?edit=' . $row['ProductID'];
            ?>
            <tr data-category="<?= htmlspecialchars($row['ProductCategory']) ?>">
              <td><?= htmlspecialchars($row['ProductCategory']) ?></td>
              <td><?= htmlspecialchars($row['ProductName']) ?></td>
              <td><?= htmlspecialchars($row['Size']) ?></td>
              <td>₱<?= number_format($row['Price'], 2) ?></td>
              <td><img src="images/<?= $img ?>" alt="<?= htmlspecialchars($row['ProductName']) ?>" class="menu-image"></td>
              <td class="actions">
                <a href="<?= $edit_link ?>" class="edit-btn">Edit</a>
                <a href="menu-management.php?delete=<?= $row['ProductID'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Add Item -->
      <div class="add-form">
        <h3>Add New Menu Item</h3> <br>
        <form id="addForm" method="post" enctype="multipart/form-data">
          <select name="category" required>
            <option value="">Select Category</option>
            <option>Featured</option>
            <option>Pinoy Pride</option>
            <option>Special Cakes</option>
            <option>Bicol Specialties</option>
            <option>Pasta and Noodles</option>
          </select>
          <input type="text" name="item" placeholder="Item Name" required>
          <textarea name="description" placeholder="Description" rows="2" required></textarea>
          <input type="text" name="variation" placeholder="Variation">
          <input type="number" step="0.01" name="price" placeholder="Price" required>
          <input type="file" name="image" accept="image/*" required>
          <button type="submit" name="add_item">Add Item</button>
        </form>
      </div>

      <!-- Edit Modal -->
      <?php if ($edit_row): ?>
        <div class="modal" style="display:flex;">
          <div class="modal-content">
            <h3>Edit Menu Item</h3> <br>
            <form method="post" enctype="multipart/form-data">
              <input type="hidden" name="edit_id" value="<?= $edit_row['ProductID'] ?>">
              <select name="edit_category" required>
                <option <?= $edit_row['ProductCategory'] == 'Featured' ? 'selected' : '' ?>>Featured</option>
                <option <?= $edit_row['ProductCategory'] == 'Pinoy Pride' ? 'selected' : '' ?>>Pinoy Pride</option>
                <option <?= $edit_row['ProductCategory'] == 'Special Cakes' ? 'selected' : '' ?>>Special Cakes</option>
                <option <?= $edit_row['ProductCategory'] == 'Bicol Specialties' ? 'selected' : '' ?>>Bicol Specialties</option>
                <option <?= $edit_row['ProductCategory'] == 'Pasta and Noodles' ? 'selected' : '' ?>>Pasta and Noodles</option>
              </select>
              <input type="text" name="edit_item" value="<?= htmlspecialchars($edit_row['ProductName']) ?>" required>
              <textarea name="edit_description" rows="2" required><?= htmlspecialchars($edit_row['ProductDescription'] ?? '') ?></textarea>
              <input type="text" name="edit_variation" value="<?= htmlspecialchars($edit_row['Size']) ?>">
              <input type="number" step="0.01" name="edit_price" value="<?= htmlspecialchars($edit_row['Price']) ?>" required>
              <label for="edit_image">Image</label>
              <input type="file" name="edit_image" accept="image/*">
              <div style="margin-bottom:1rem;">
                <img src="images/<?= $edit_row['Image'] ? htmlspecialchars($edit_row['Image']) : 'placeholder.jpg' ?>" alt="Image preview" style="width: 100px; height: 100px; object-fit: cover; border-radius: 0.5rem;">
              </div>
              <button type="submit" name="edit_item">Save Changes</button>
              <a href="menu-management.php" style="margin-left:1rem;">Cancel</a>
            </form>
          </div>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['success'])): ?>
        <div id="toast" style="opacity:1;">Item added!</div>
      <?php elseif (isset($_GET['edited'])): ?>
        <div id="toast" style="opacity:1;">Item updated!</div>
      <?php elseif (isset($_GET['deleted'])): ?>
        <div id="toast" style="opacity:1;">Item deleted!</div>
      <?php endif; ?>

    </div>
    <footer>
      &copy; 2025 Dhen's Kitchen. All rights reserved.
    </footer>
  </div>
  <script>
    // Simple filter for search/category (client-side)
    function filterMenu() {
      const search = document.getElementById('searchInput').value.toLowerCase();
      const category = document.getElementById('categoryFilter').value;
      const rows = document.querySelectorAll('#menuTable tbody tr');
      rows.forEach(row => {
        const item = row.children[1].innerText.toLowerCase();
        const rowCategory = row.dataset.category;
        const matchesSearch = item.includes(search);
        const matchesCategory = category === "" || rowCategory === category;
        row.style.display = (matchesSearch && matchesCategory) ? '' : 'none';
      });
    }
    document.getElementById('searchInput').addEventListener('input', filterMenu);
    document.getElementById('categoryFilter').addEventListener('change', filterMenu);
    // Hide toast after 2s
    setTimeout(() => {
      const toast = document.getElementById('toast');
      if (toast) toast.style.opacity = 0;
    }, 2000);

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
  </script>
</body>

</html>