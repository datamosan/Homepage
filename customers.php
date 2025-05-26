<?php
session_start();
?>

<?php
require 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Customers - DecaDhen: Dhen's Kitchen</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;600&display=swap" rel="stylesheet" />
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
    * { margin: 0; padding: 0; box-sizing: border-box; }
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
    .controls {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
      flex-wrap: wrap;
      margin-left: 2rem;
      margin-right: 2rem;
    }
    .controls input[type="text"] {
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
    th, td {
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
      background-color: rgba(255,255,255,0.15);
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
    .main-content > footer {
      margin-top: auto;
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
      <a href="view-orders.php"><i class="fas fa-receipt"></i>View Orders</a>
      <a href="menu-management.php"><i class="fas fa-utensils"></i> Manage Menu</a>
      <a href="order-management.php"><i class="fas fa-box"></i>Manage Orders</a>
      <a href="messages.php"><i class="fas fa-envelope"></i>Messages</a>
      <a href="customers.php" class="active"><i class="fas fa-users"></i>Customer Data</a>
      <a href="index.php"><i class="fas fa-user"></i>Logout</a>
    </nav>
  </aside>

  <div class="main-content">
    <header>
      <div class="icons">
        <i class="fas fa-bell"></i>
        <i class="fas fa-user-circle"></i>
      </div>
    </header>

    <h1>Customer Data</h1>

    <div class="controls">
      <input type="text" id="searchInput" placeholder="Search by name or email..." />
      <button id="exportBtn"><i class="fas fa-file-export"></i> Export CSV</button>
    </div>

    <table id="customerTable">
      <thead>
        <tr>
          <th class="sortable" data-key="UserID">UserID <i class="fas fa-sort"></i></th>
          <th class="sortable" data-key="UserName">Name <i class="fas fa-sort"></i></th>
          <th class="sortable" data-key="UserEmail">Email <i class="fas fa-sort"></i></th>
          <th>Phone</th>
          <th>Address</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Fetch customer data from the database
        $sql = "SELECT UserID, UserName, UserEmail, UserPhone, UserAddress FROM users";
        $result = $conn->query($sql);
        $customers = [];
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['UserID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['UserName']) . "</td>";
            echo "<td>" . htmlspecialchars($row['UserEmail']) . "</td>";
            echo "<td>" . htmlspecialchars($row['UserPhone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['UserAddress']) . "</td>";
            echo "</tr>";
          }
        } else {
          echo '<tr><td colspan="5" style="text-align:center; padding:1rem;">No customers found.</td></tr>';
        }
        ?>
      </tbody>
    </table>

    <footer>
      &copy; 2025 Dhen's Kitchen. All rights reserved.
    </footer>
  </div>
  <script>
    // Collect table data into JS array for sorting/filtering
    const table = document.getElementById('customerTable');
    const headers = table.querySelectorAll('th.sortable');
    const searchInput = document.getElementById('searchInput');
    const exportBtn = document.getElementById('exportBtn');
    let rows = Array.from(table.querySelectorAll('tbody tr'));
    let currentSort = { key: null, asc: true };

    // Filter by name or email
    searchInput.addEventListener('input', function() {
      const filter = this.value.trim().toLowerCase();
      rows.forEach(row => {
        const name = row.children[1]?.textContent.toLowerCase() || '';
        const email = row.children[2]?.textContent.toLowerCase() || '';
        if (name.includes(filter) || email.includes(filter) || filter === '') {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });

    // Sorting function
    headers.forEach(header => {
      header.addEventListener('click', function() {
        const key = this.getAttribute('data-key');
        let colIdx;
        if (key === 'UserID') colIdx = 0;
        else if (key === 'UserName') colIdx = 1;
        else if (key === 'UserEmail') colIdx = 2;
        else return;
        if (currentSort.key === key) {
          currentSort.asc = !currentSort.asc;
        } else {
          currentSort.key = key;
          currentSort.asc = true;
        }
        // Sort rows array
        rows.sort((a, b) => {
          let valA = a.children[colIdx].textContent;
          let valB = b.children[colIdx].textContent;
          // Numeric sort for UserID, string sort for others
          if (key === 'UserID') {
            valA = parseInt(valA, 10);
            valB = parseInt(valB, 10);
            if (isNaN(valA)) valA = 0;
            if (isNaN(valB)) valB = 0;
          } else {
            valA = valA.toLowerCase();
            valB = valB.toLowerCase();
          }
          if (valA < valB) return currentSort.asc ? -1 : 1;
          if (valA > valB) return currentSort.asc ? 1 : -1;
          return 0;
        });
        // Remove all rows and re-append in sorted order
        const tbody = table.querySelector('tbody');
        rows.forEach(row => tbody.appendChild(row));
        updateSortIcons();
      });
    });

    function updateSortIcons() {
      headers.forEach(header => {
        const icon = header.querySelector('i');
        if (header.getAttribute('data-key') === currentSort.key) {
          icon.className = currentSort.asc ? "fas fa-sort-up" : "fas fa-sort-down";
          header.classList.add("active");
        } else {
          icon.className = "fas fa-sort";
          header.classList.remove("active");
        }
      });
    }

    // Export CSV functionality
    exportBtn.addEventListener('click', function() {
      const visibleRows = Array.from(table.querySelectorAll('tbody tr')).filter(row => row.style.display !== 'none');
      let csv = [];
      // Get headers
      const headerCols = Array.from(table.querySelectorAll('thead th')).map(col => col.innerText.replace(/\s+/g, ' ').trim());
      csv.push(headerCols.join(','));
      // Get rows
      visibleRows.forEach(row => {
        const cols = Array.from(row.querySelectorAll('td')).map(col => {
          let text = col.innerText.replace(/"/g, '""');
          if (text.search(/("|,|\n)/g) >= 0) text = `"${text}"`;
          return text;
        });
        csv.push(cols.join(','));
      });
      const csvContent = "data:text/csv;charset=utf-8," + csv.join('\r\n');
      const link = document.createElement('a');
      link.setAttribute('href', encodeURI(csvContent));
      link.setAttribute('download', 'customers.csv');
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    });

    // Initial sort icon state
    updateSortIcons();
  </script>
</body>
</html>
