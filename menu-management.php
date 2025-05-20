<?php
include 'connection.php';

// Handle Add New Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $item = mysqli_real_escape_string($conn, $_POST['item']);
    $variation = mysqli_real_escape_string($conn, $_POST['variation']);
    $price = floatval($_POST['price']);

    // Handle image upload
    $image_filename = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_filename = uniqid('menu_', true) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/' . $image_filename);
    }

    // Insert into products table (if product doesn't exist)
    $product_check = mysqli_query($conn, "SELECT ProductID FROM products WHERE ProductName='$item' AND ProductCategory='$category'");
    if ($product_row = mysqli_fetch_assoc($product_check)) {
        $product_id = $product_row['ProductID'];
        // Optionally update image if new image uploaded
        if ($image_filename) {
            mysqli_query($conn, "UPDATE products SET Image='$image_filename' WHERE ProductID='$product_id'");
        }
    } else {
        mysqli_query($conn, "INSERT INTO products (ProductName, ProductCategory, Image) VALUES ('$item', '$category', '$image_filename')");
        $product_id = mysqli_insert_id($conn);
    }

    // Insert into product_attributes
    if ($variation || $price) {
        mysqli_query($conn, "INSERT INTO product_attributes (ProductID, Size, Price) VALUES ('$product_id', '$variation', '$price')");
    }

    header("Location: menu-management.php?success=1");
    exit;
}

// Handle Edit Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_item'])) {
    $edit_id = intval($_POST['edit_id']);
    $category = mysqli_real_escape_string($conn, $_POST['edit_category']);
    $item = mysqli_real_escape_string($conn, $_POST['edit_item']);
    $variation = mysqli_real_escape_string($conn, $_POST['edit_variation']);
    $price = floatval($_POST['edit_price']);

    // Handle image upload
    if (isset($_FILES['edit_image']) && $_FILES['edit_image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['edit_image']['name'], PATHINFO_EXTENSION);
        $image_filename = uniqid('menu_', true) . '.' . $ext;
        move_uploaded_file($_FILES['edit_image']['tmp_name'], 'images/' . $image_filename);
        mysqli_query($conn, "UPDATE products SET Image='$image_filename' WHERE ProductID='$edit_id'");
    }

    // Update products table
    mysqli_query($conn, "UPDATE products SET ProductName='$item', ProductCategory='$category' WHERE ProductID='$edit_id'");

    // Update product_attributes (assumes one attribute per product for simplicity)
    $attr_check = mysqli_query($conn, "SELECT SizeID FROM product_attributes WHERE ProductID='$edit_id'");
    if ($attr_row = mysqli_fetch_assoc($attr_check)) {
        $size_id = $attr_row['SizeID'];
        mysqli_query($conn, "UPDATE product_attributes SET Size='$variation', Price='$price' WHERE SizeID='$size_id'");
    } else {
        mysqli_query($conn, "INSERT INTO product_attributes (ProductID, Size, Price) VALUES ('$edit_id', '$variation', '$price')");
    }

    header("Location: menu-management.php?edited=1");
    exit;
}

// Handle Delete Item
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    // Delete product attributes first (to avoid foreign key issues)
    mysqli_query($conn, "DELETE FROM product_attributes WHERE ProductID='$delete_id'");
    // Delete product
    mysqli_query($conn, "DELETE FROM products WHERE ProductID='$delete_id'");
    header("Location: menu-management.php?deleted=1");
    exit;
}

// Fetch all menu items
$query = "
    SELECT p.ProductID, p.ProductCategory, p.ProductName, pa.SizeID, pa.Size, pa.Price, p.Image
    FROM products p
    LEFT JOIN product_attributes pa ON p.ProductID = pa.ProductID
    ORDER BY p.ProductCategory, p.ProductName, pa.Size
";
$result = mysqli_query($conn, $query);

// For Edit Modal
$edit_row = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "
        SELECT p.ProductID, p.ProductCategory, p.ProductName, pa.SizeID, pa.Size, pa.Price, p.Image
        FROM products p
        LEFT JOIN product_attributes pa ON p.ProductID = pa.ProductID
        WHERE p.ProductID = $edit_id
        LIMIT 1
    ";
    $edit_result = mysqli_query($conn, $edit_query);
    $edit_row = mysqli_fetch_assoc($edit_result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menu Management - DecaDhen Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #fffdf6;
      --sidebar-bg: #294C3B;
      --header-bg: #294C3B;
      --text-light: #fff;
      --text-dark: #2f2f2f;
      --accent: #CF5C5C;
      --card-bg: #FFF4E6;
      --button-bg: #294C3B;
      --radius: 1.25rem;
      --shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      display: flex;
      background: var(--bg);
      color: var(--text-dark);
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: var(--sidebar-bg);
      height: 100vh;
      padding: 2rem 1rem;
      position: fixed;
      display: flex;
      flex-direction: column;
      gap: 2rem;
      color: var(--text-light);
    }

    .sidebar .logo {
      font-family: 'DM Serif Display', serif;
      font-size: 1.7rem;
      text-align: center;
    }

    .sidebar nav a {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      color: var(--text-light);
      text-decoration: none;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      font-weight: 600;
      transition: background 0.3s;
    }

    .sidebar nav a:hover, .sidebar nav a.active {
      background: rgba(255, 255, 255, 0.1);
    }

    /* Main content */
    .main {
      margin-left: 250px;
      width: calc(100% - 250px);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background: var(--header-bg);
      color: var(--text-light);
      padding: 1rem 2rem;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      box-shadow: var(--shadow);
    }

    header .notification {
      position: relative;
      font-size: 1.3rem;
      cursor: pointer;
    }

    .notification .badge {
      position: absolute;
      top: -6px;
      right: -10px;
      background: var(--accent);
      color: white;
      width: 16px;
      height: 16px;
      border-radius: 50%;
      font-size: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .content {
      padding: 2rem;
    }

    h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      color: var(--sidebar-bg);
      margin-bottom: 1rem;
    }

    .filter-bar {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .filter-bar input, .filter-bar select {
      padding: 0.6rem 1rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
      flex: 1;
    }

    table {
      width: 100%;
      background: var(--card-bg);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      border-collapse: collapse;
    }

    th, td {
      padding: 1rem;
      border-bottom: 1px solid #ddd;
      text-align: left;
      vertical-align: middle;
    }

    th {
      background: #fff8e6;
      color: var(--sidebar-bg);
    }

    /* Image in table */
    td img.menu-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 0.5rem;
      margin-right: 0.5rem;
      vertical-align: middle;
    }

    .actions button {
      margin-right: 0.5rem;
      border: none;
      padding: 0.4rem 0.8rem;
      border-radius: 0.5rem;
      font-weight: 600;
      cursor: pointer;
    }

    .edit-btn,
    .delete-btn {
      background: none;
      border: none;
      color: var(--button-bg); /* dark green for edit */
      font-weight: 600;
      padding: 0;
      margin: 0 0.5rem 0 0;
      border-radius: 0;
      text-decoration: none;   /* <-- Add this line */
      cursor: pointer;
      transition: color 0.2s;
      font-size: 1rem;
      box-shadow: none;
      outline: none;
      display: inline;
    }

    .edit-btn:hover {
      color: var(--accent); /* red accent on hover */
      text-decoration: none;
    }

    .delete-btn {
      color: var(--accent); /* red */
      text-decoration: none;   /* <-- Add this line */
    }

    .delete-btn:hover {
      color: #a94442;
      text-decoration: none;
    }

    .add-form {
      margin-top: 2rem;
      background: var(--card-bg);
      padding: 1.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .add-form input, .add-form select {
      width: 100%;
      padding: 0.6rem 1rem;
      margin-bottom: 1rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
    }

    .add-form button {
      background: var(--button-bg);
      color: white;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 0.5rem;
      font-weight: 600;
      cursor: pointer;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      width: 90%;
      max-width: 400px;
      box-shadow: var(--shadow);
    }

    .modal-content input, .modal-content select {
      width: 100%;
      padding: 0.6rem 1rem;
      margin-bottom: 1rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
    }

    .modal-content button {
      background: var(--button-bg);
      color: white;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 0.5rem;
      font-weight: 600;
      cursor: pointer;
    }

    /* Toast */
    #toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--button-bg);
      color: white;
      padding: 1rem 1.5rem;
      border-radius: var(--radius);
      opacity: 0;
      transition: opacity 0.5s;
      box-shadow: var(--shadow);
    }
  </style>
</head>
<body>

  <aside class="sidebar">
    <div class="logo">DecaDhen</div>
    <nav>
      <a href="dashboard.html"><i class="fas fa-home"></i> Home</a>
      <a href="view-orders.html"><i class="fas fa-receipt"></i> View Orders</a>
      <a href="menu-management.php" class="active"><i class="fas fa-utensils"></i> Menu Management</a>
      <a href="order-management.html"><i class="fas fa-box"></i> Order Management</a>
      <a href="messages.html"><i class="fas fa-envelope"></i> Messages</a>
      <a href="customers.html"><i class="fas fa-users"></i> Customer Data</a>
    </nav>
  </aside>

  <div class="main">
    <header>
      <div class="notification">
        <i class="fas fa-bell"></i>
        <span class="badge">3</span>
      </div>
    </header>

    <div class="content">
      <h1>Menu Management</h1>

      <!-- Filter/Search -->
      <div class="filter-bar">
        <input type="text" placeholder="Search item..." id="searchInput" onkeyup="filterMenu()">
        <select id="categoryFilter" onchange="filterMenu()">
          <option value="">All Categories</option>
          <option>Customized Cakes</option>
          <option>Special Cakes</option>
          <option>Kakanin</option>
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
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
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
        <h3>Add New Menu Item</h3>
        <form id="addForm" method="post" enctype="multipart/form-data">
          <select name="category" required>
            <option value="">Select Category</option>
            <option>Customized Cakes</option>
            <option>Special Cakes</option>
            <option>Kakanin</option>
            <option>Pasta and Noodles</option>
          </select>
          <input type="text" name="item" placeholder="Item Name" required>
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
          <h3>Edit Menu Item</h3>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="edit_id" value="<?= $edit_row['ProductID'] ?>">
            <select name="edit_category" required>
              <option <?= $edit_row['ProductCategory']=='Customized Cakes'?'selected':'' ?>>Customized Cakes</option>
              <option <?= $edit_row['ProductCategory']=='Special Cakes'?'selected':'' ?>>Special Cakes</option>
              <option <?= $edit_row['ProductCategory']=='Kakanin'?'selected':'' ?>>Kakanin</option>
              <option <?= $edit_row['ProductCategory']=='Pasta and Noodles'?'selected':'' ?>>Pasta and Noodles</option>
            </select>
            <input type="text" name="edit_item" value="<?= htmlspecialchars($edit_row['ProductName']) ?>" required>
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
  </script>
</body>
</html>

