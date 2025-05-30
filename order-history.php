<?php
session_start();
require_once 'connection.php';

// Fetch only completed or rejected orders
$sql = "SELECT o.OrderDate, u.UserName, o.OrderID, o.OrderStatus, o.OrderDeadline, o.PaymentProof
        FROM orders o
        JOIN users u ON o.UserID = u.UserID
        WHERE o.OrderStatus IN ('Completed', 'Rejected')
        ORDER BY o.OrderDate DESC";
$result = $conn->query($sql);
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
    .new { background: var(--teal); }
    .received { background: var(--orange); }
    .processing { background: var(--lavender); }
    .delivering { background: var(--mint); }
    .completed { background:rgb(43, 126, 46); color: white; }
    .rejected { background:var(--coral); color: #333; }
    
    .btn {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 0.5rem;
      cursor: pointer;
      font-weight: 600;
      background-color: var(--teal);
      color: white;
      margin-right: 0.5rem;
      transition: background 0.3s;
    }
    .btn:hover {
      background-color: var(--coral);
    }
    
    button.delete-btn {
      background-color: #d9534f;
      color: white;
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 0.5rem;
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

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 2rem;
      border-radius: var(--radius);
      width: 90%;
      max-width: 900px;
      box-shadow: var(--shadow);
    }

    #processModal .modal-content {
      max-width: 400px;
    }

    .modal-content h2 {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      margin-bottom: 1rem;
      color: var(--teal);
    }
    .modal-content p {
      margin: 0.5rem 0;
      color: var(--dark-gray);
    }
    .modal-buttons {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
    }
    .modal-buttons .btn {
      flex: 1;
    }
    #processForm label {
      display: block;
      margin: 0.75rem 0 0.3rem;
      font-weight: 600;
      color: var(--teal);
    }
    #processForm select,
    #processForm textarea {
      width: 100%;
      padding: 0.5rem;
      border-radius: 0.5rem;
      border: 1px solid #ccc;
      resize: vertical;
      font-family: 'Inter', sans-serif;
    }
    #processForm textarea {
      min-height: 80px;
    }
    
    #modalOrderItems {
      display: flex;
      justify-content: center;
      margin-top: 1.5em;
    }

    .modal-content .modal-info {
      width: 100%;
      max-width: 600px;
      margin: 0 0 0.5em 2em;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }
    .modal-content .modal-info p {
      margin-left: 0;
      margin-right: 0;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .modal-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 0.5rem;
      margin-top: 1rem;
    }

    /* Responsive adjustments for modals */
    @media (max-width: 900px) {
      .modal-content {
        width: 90%;
        padding: 15px;
      }

      .modal-buttons {
        flex-direction: column;
        align-items: stretch;
      }

      .modal-buttons button {
        width: 100%;
        padding: 0.7rem;
        font-size: 0.95rem;
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
      <a href="view-orders.php"><i class="fas fa-receipt"></i>Manage Orders</a>
      <a href="menu-management.php"><i class="fas fa-utensils"></i> Manage Menu</a>
      <a href="order-history.php" class="active"><i class="fas fa-box"></i>Orders History</a>
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
          <th>Customer Name</th>
          <th>Order ID</th>
          <th>Proof of Payment</th>
          <th>Status</th>
          <th>Deadline</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="orderTableBody">
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['OrderDate']); ?></td>
              <td><?php echo htmlspecialchars($row['UserName']); ?></td>
              <td>#<?php echo htmlspecialchars($row['OrderID']); ?></td>
              <td>
                <?php if (!empty($row['PaymentProof'])): ?>
                  <a href="<?php echo htmlspecialchars($row['PaymentProof']); ?>" target="_blank" style="color: var(--teal); font-weight: 600;">View</a>
                <?php else: ?>
                  <span style="color:#aaa;">No Proof</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="status-badge <?php echo strtolower(trim($row['OrderStatus'])); ?>">
                  <?php echo ucfirst(strtolower(trim($row['OrderStatus']))); ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($row['OrderDeadline']); ?></td>
              <td>
                <button class="btn" onclick="openModal('view', this)">View</button>
                <button class="btn" onclick="openModal('process', this)">Process</button>
                <button class="delete-btn" onclick="deleteOrder(<?php echo $row['OrderID']; ?>)">Delete</button>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" style="text-align:center;">No completed or rejected orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <footer>
      &copy; 2025 Dhen's Kitchen. All rights reserved.
    </footer>
  </div>

  <!-- View Modal -->
<div id="viewModal" class="modal" onclick="closeModal(event)">
  <div class="modal-content" onclick="event.stopPropagation()">
    <h2>Order Details</h2>
    <div class="modal-info">
      <p><strong>Customer:</strong> <span id="modalCustomer"></span></p>
      <p><strong>Order ID:</strong> <span id="modalOrderId"></span></p>
      <p><strong>Date:</strong> <span id="modalDate"></span></p>
      <p><strong>Deadline:</strong> <span id="modalDeadline"></span></p>
      <p><strong>Proof of Payment:</strong> <a href="#" target="_blank" id="modalProof" rel="noopener noreferrer">View Image</a></p>
      <p><strong>Status:</strong> <span id="modalStatus"></span></p>
    </div>
    <div id="modalOrderItems"></div>
    <div class="modal-buttons">
      <button class="btn" onclick="openModal('process')">Process Order</button>
      <button class="btn" onclick="closeModal()">Close</button>
    </div>
  </div>
</div>

<!-- Process Modal -->
<div id="processModal" class="modal" onclick="closeModal(event)">
  <div class="modal-content" onclick="event.stopPropagation()">
    <h2>Process Order</h2>
    <form id="processForm" onsubmit="submitProcess(event)">
      <label for="processStatus">Status:</label>
      <select id="processStatus" required>
        <option value="Received">Received</option>
        <option value="Processing">Processing</option>
        <option value="Delivering">Delivering</option>
        <option value="Completed">Completed</option>
        <option value="Rejected">Rejected</option>
      </select>
      <label for="remarks">Remarks:</label>
      <textarea id="remarks" placeholder="Enter remarks here..."></textarea>
      <div class="modal-buttons">
        <button type="submit" class="btn">Submit</button>
        <button type="button" class="btn" onclick="closeModal()">Cancel</button>
      </div>
    </form>
  </div>
</div>

  <script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const sortField = document.getElementById('sortField');
  const sortOrder = document.getElementById('sortOrder');
  const orderTableBody = document.getElementById('orderTableBody');

  function filterAndSortTable() {
    const search = searchInput.value.trim().toLowerCase();
    const field = sortField.value;
    const order = sortOrder.value;

    // Convert rows to array for sorting/filtering
    let rows = Array.from(orderTableBody.querySelectorAll('tr'));

    // Filter by search (name or order ID)
    rows.forEach(row => {
      const name = row.children[1]?.textContent.toLowerCase() || '';
      const orderId = row.children[2]?.textContent.toLowerCase() || '';
      if (name.includes(search) || orderId.includes(search) || !search) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });

    // Sort visible rows
    rows = rows.filter(row => row.style.display !== 'none');
    rows.sort((a, b) => {
      let aVal, bVal;
      if (field === 'deadline') {
        aVal = a.children[5]?.textContent || '';
        bVal = b.children[5]?.textContent || '';
      } else if (field === 'status') {
        aVal = a.children[4]?.textContent || '';
        bVal = b.children[4]?.textContent || '';
      } else {
        aVal = '';
        bVal = '';
      }
      if (order === 'asc') {
        return aVal.localeCompare(bVal, undefined, {numeric: true});
      } else {
        return bVal.localeCompare(aVal, undefined, {numeric: true});
      }
    });

    // Re-append sorted rows
    rows.forEach(row => orderTableBody.appendChild(row));
  }

  searchInput.addEventListener('input', filterAndSortTable);
  sortField.addEventListener('change', filterAndSortTable);
  sortOrder.addEventListener('change', filterAndSortTable);

  // Optional: sort button
  document.getElementById('sortBtn').addEventListener('click', filterAndSortTable);

  // Initial sort/filter
  filterAndSortTable();
});

function deleteOrder(orderId) {
  if (!confirm('Are you sure you want to delete this order? This action cannot be undone.')) return;
  fetch('delete-order.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'orderId=' + encodeURIComponent(orderId)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // Remove the row from the table
      const row = Array.from(orderTableBody.querySelectorAll('tr')).find(tr =>
        tr.children[2] && tr.children[2].textContent.replace('#', '').trim() == orderId
      );
      if (row) row.remove();
      alert('Order deleted successfully.');
    } else {
      alert('Failed to delete order.');
    }
  });
}

let currentProcessingRow = null;
let currentViewRow = null;

function openModal(type, btn = null) {
  if (type === 'view') {
    if (!btn) return;
    const row = btn.closest('tr');
    currentViewRow = row;

    document.getElementById('modalCustomer').innerText = row.children[1].innerText;
    document.getElementById('modalOrderId').innerText = row.children[2].innerText;
    document.getElementById('modalDate').innerText = row.children[0].innerText;
    document.getElementById('modalDeadline').innerText = row.children[5].innerText;

    const proofAnchor = row.children[3].querySelector('a');
    if (proofAnchor) {
      document.getElementById('modalProof').href = proofAnchor.href;
      document.getElementById('modalProof').style.display = '';
    } else {
      document.getElementById('modalProof').href = '#';
      document.getElementById('modalProof').style.display = 'none';
    }

    document.getElementById('modalStatus').innerText = row.children[4].innerText;

    // --- Fetch and display order items ---
    const orderId = row.children[2].innerText.replace('#', '').trim();
    fetch('get-order-details.php?orderId=' + encodeURIComponent(orderId))
      .then(res => res.json())
      .then(data => {
        let html = '';
        if (data.products && data.products.length > 0) {
          html += '<table style="width:100%;margin-top:1em;border-collapse:collapse;">';
          html += '<tr><th style="text-align:left;">Product</th><th>Size</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>';
          data.products.forEach(item => {
            html += `<tr>
              <td>${item.name}</td>
              <td style="text-align:left;">${item.size || '-'}</td>
              <td style="text-align:center;">${item.qty}</td>
              <td style="text-align:left;">₱${parseFloat(item.price).toFixed(2)}</td>
              <td style="text-align:left;">₱${parseFloat(item.subtotal).toFixed(2)}</td>
            </tr>`;
          });
          html += `<tr>
            <td colspan="4" style="text-align:right;font-weight:bold;">Total:</td>
            <td style="text-align:left;font-weight:bold;">₱${parseFloat(data.total).toFixed(2)}</td>
          </tr>`;
          html += '</table>';
        } else {
          html = '<em>No products found for this order.</em>';
        }
        document.getElementById('modalOrderItems').innerHTML = html;
      });

    document.getElementById('viewModal').style.display = 'flex';
  } else if (type === 'process') {
    if (btn) {
      currentProcessingRow = btn.closest('tr');
    } else if (currentViewRow) {
      currentProcessingRow = currentViewRow;
      closeModal();
    }
    if (!currentProcessingRow) return alert('No order selected.');

    // Pre-select current status
    const currentStatus = currentProcessingRow.children[4].innerText.replace(/<[^>]*>?/gm, '').trim();
    document.getElementById('processStatus').value = currentStatus;
    document.getElementById('remarks').value = '';

    document.getElementById('processModal').style.display = 'flex';
  }
}

function closeModal(event) {
  if (event && event.target !== event.currentTarget) return;
  document.getElementById('viewModal').style.display = 'none';
  document.getElementById('processModal').style.display = 'none';
}

function submitProcess(event) {
  event.preventDefault();
  if (!currentProcessingRow) return alert('No order selected.');

  const status = document.getElementById('processStatus').value;
  const remarks = document.getElementById('remarks').value;
  const orderId = currentProcessingRow.children[2].innerText.replace('#', '').trim();

  fetch('update-order-status.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `orderId=${encodeURIComponent(orderId)}&status=${encodeURIComponent(status)}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // Update status text in the row
      currentProcessingRow.children[4].innerHTML =
        `<span class="status-badge ${status.toLowerCase()}">${status}</span>`;
      // Optionally update data-status attribute for filtering
      currentProcessingRow.dataset.status = status;
      alert(`Order status updated to "${status}".`);
      closeModal();
      // Optionally, remove the row if status is no longer Completed/Rejected
      if (status !== 'Completed' && status !== 'Rejected') {
        currentProcessingRow.remove();
      }
    } else {
      alert('Failed to update order status.');
    }
  });
}
  </script>
</body>
</html>