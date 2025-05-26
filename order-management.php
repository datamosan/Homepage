<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order Management - DecaDhen</title>
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
      gap: 0.5rem;
      margin-bottom: 1rem;
      margin-left: 2rem;   /* Match table's margin */
      margin-right: 2rem;  /* Match table's margin */
      flex-wrap: nowrap;
      overflow-x: visible;
      white-space: nowrap;
      font-size: 0.97rem;
    }
    .controls input,
    .controls select,
    .controls button {
      padding: 0.5rem 1rem; /* Match customers.php */
      border-radius: var(--radius);
      border: 1px solid #ccc;
      min-width: 0;
      flex-shrink: 1;
      max-width: 140px;
    }
    .controls button {
      background-color: var(--teal);
      color: var(--white);
      border: none;
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
    .controls #searchInput {
      min-width: 220px;
      max-width: 320px;
      flex-basis: 220px;
    }
    .controls #sortField {
      min-width: 160px;
      max-width: 220px;
      flex-basis: 160px;
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
    th {
      font-weight: 600;
    }
    tr:last-child td {
      border-bottom: none;
    }
    .status-badge {
      padding: 6px 12px;
      border-radius: 12px;
      color: white;
      font-weight: bold;
      font-size: 0.9em;
      display: inline-block;
    }
    .preparing { background: #ff9800; }
    .out-for-delivery { background: #2196f3; }
    .delivered { background: #4caf50; }
    button.delete-btn {
      background-color: var(--coral);
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.3s;
    }
    button.delete-btn:hover {
      background-color: #a94444;
    }
    .main-content > footer {
      margin-top: auto;
      text-align: center;
      padding: 1rem;
      background-color: var(--light-gray);
      font-size: 0.9rem;
      color: #555;
    }
    @media (max-width: 900px) {
      .sidebar { width: 100px; }
      .main-content { margin-left: 100px; width: calc(100% - 100px);}
      table, .controls { margin-left: 1rem; margin-right: 1rem; }
      .controls {
        gap: 0.3rem;
        font-size: 0.92rem;
      }
      .controls input,
      .controls select,
      .controls button {
        padding: 0.25rem 0.4rem;
        font-size: 0.92rem;
        max-width: 90px;
      }
      .controls #searchInput {
        min-width: 110px;
        max-width: 180px;
        flex-basis: 110px;
      }
      .controls #sortField {
        min-width: 90px;
        max-width: 120px;
        flex-basis: 90px;
      }
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
      <a href="order-management.php" class="active"><i class="fas fa-box"></i>Manage Orders</a>
      <a href="messages.php"><i class="fas fa-envelope"></i>Messages</a>
      <a href="customers.php"><i class="fas fa-users"></i>Customer Data</a>
      <a href="logout.php"><i class="fas fa-user"></i>Logout</a>
    </nav>
  </aside>

  <div class="main-content">
    <header>
      <div class="icons">
        <i class="fas fa-bell"></i>
        <i class="fas fa-user-circle"></i>
      </div>
    </header>

    <h1>Order Management</h1>

    <div class="controls">
      <input type="text" id="searchInput" placeholder="Search by name or order ID">
      <input type="date" id="startDate">
      <input type="date" id="endDate">
      <button id="filterDateBtn"><i class="fas fa-filter"></i> Filter</button>
      <select id="sortField">
        <option value="deadline">Sort by Deadline</option>
        <option value="status">Sort by Status</option>
      </select>
      <select id="sortOrder">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
      </select>
      <button id="sortBtn"><i class="fas fa-sort"></i> Sort</button>
      <button id="exportBtn"><i class="fas fa-file-export"></i> Export CSV</button>
      <button id="printBtn"><i class="fas fa-print"></i> Print</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Name</th>
          <th>Order ID</th>
          <th>Proof</th>
          <th>Deadline</th>
          <th>Status</th>
          <th>Update</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="orderTableBody"></tbody>
    </table>
    <footer>
      &copy; 2025 Dhen's Kitchen. All rights reserved.
    </footer>
  </div>

  <script>
    const tableBody = document.getElementById('orderTableBody');
    const searchInput = document.getElementById('searchInput');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');

    function loadOrders() {
      const orders = JSON.parse(localStorage.getItem('approvedOrders') || '[]');
      tableBody.innerHTML = '';
      orders.forEach((o, i) => {
        const badgeClass = o.status === 'Preparing' ? 'preparing' :
                           o.status === 'Out for Delivery' ? 'out-for-delivery' : 'delivered';
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${o.date}</td>
          <td>${o.name}</td>
          <td>${o.orderId}</td>
          <td><a href="${o.proof}" target="_blank">View</a></td>
          <td>${o.deadline}</td>
          <td><span class="status-badge ${badgeClass}">${o.status}</span></td>
          <td>
            <select onchange="updateStatus(${i}, this.value)">
              <option value="Preparing" ${o.status==='Preparing'?'selected':''}>Preparing</option>
              <option value="Out for Delivery" ${o.status==='Out for Delivery'?'selected':''}>Out for Delivery</option>
              <option value="Delivered" ${o.status==='Delivered'?'selected':''}>Delivered</option>
            </select>
          </td>
          <td><button class="delete-btn" onclick="deleteOrder(${i})">Delete</button></td>
        `;
        tableBody.appendChild(tr);
      });
      applyFilters();
    }

    function updateStatus(index, newStatus) {
      const orders = JSON.parse(localStorage.getItem('approvedOrders') || '[]');
      orders[index].status = newStatus;
      localStorage.setItem('approvedOrders', JSON.stringify(orders));
      loadOrders();
    }

    function deleteOrder(index) {
      let orders = JSON.parse(localStorage.getItem('approvedOrders') || '[]');
      if (confirm('Delete this order?')) {
        orders.splice(index, 1);
        localStorage.setItem('approvedOrders', JSON.stringify(orders));
        loadOrders();
      }
    }

    function exportToCSV() {
      const orders = JSON.parse(localStorage.getItem('approvedOrders') || '[]');
      let csv = 'Date,Name,Order ID,Deadline,Status\n';
      orders.forEach(o => {
        csv += `${o.date},${o.name},${o.orderId},${o.deadline},${o.status}\n`;
      });
      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = 'approved_orders.csv';
      link.click();
    }

    function printPDF() {
      window.print();
    }

    function applyFilters() {
      const q = searchInput.value.toLowerCase();
      const sd = startDate.value;
      const ed = endDate.value;
      Array.from(tableBody.rows).forEach(row => {
        const name = row.cells[1].innerText.toLowerCase();
        const id = row.cells[2].innerText.toLowerCase();
        const dl = row.cells[4].innerText;
        let visible = (name.includes(q) || id.includes(q));
        if (visible && sd) visible = dl >= sd;
        if (visible && ed) visible = dl <= ed;
        row.style.display = visible ? '' : 'none';
      });
    }

    function sortTable() {
      const field = document.getElementById('sortField').value;
      const order = document.getElementById('sortOrder').value;
      let orders = JSON.parse(localStorage.getItem('approvedOrders') || '[]');
      orders.sort((a, b) => {
        let v1 = field === 'deadline' ? new Date(a.deadline) : a.status;
        let v2 = field === 'deadline' ? new Date(b.deadline) : b.status;
        if (v1 < v2) return order === 'asc' ? -1 : 1;
        if (v1 > v2) return order === 'asc' ? 1 : -1;
        return 0;
      });
      localStorage.setItem('approvedOrders', JSON.stringify(orders));
      loadOrders();
    }

    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('filterDateBtn').addEventListener('click', applyFilters);
    document.getElementById('sortBtn').addEventListener('click', sortTable);
    document.getElementById('exportBtn').addEventListener('click', exportToCSV);
    document.getElementById('printBtn').addEventListener('click', printPDF);

    window.addEventListener('DOMContentLoaded', loadOrders);
  </script>
</body>
</html>