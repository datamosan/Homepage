<?php
session_start();
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
      margin-bottom: 1rem; /* Reduce space below logo */
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

    .sidebar nav a:hover {
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

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      padding: 2rem;
    }

    .card {
      background-color: var(--teal);
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      transition: 
        transform 0.3s ease, 
        box-shadow 0.3s ease, 
        background-color 0.3s ease; /* Add background-color transition */
      display: flex;
      justify-content: space-between;
      gap: 0.5rem;
      align-items: center;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
      background-color: var(--coral); /* Change color on hover */
    }

    .card h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.3rem;
      color: var(--white);
      margin-bottom: 0.3rem;
    }

    .card p {
      font-size: 0.95rem;
      color: var(--light-gray);
    }

    .card i {
      font-size: 1.8rem;
      color: var(--mint);
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
      <a href="view-orders.php"><i class="fas fa-receipt"></i>Manage Orders</a>
      <a href="menu-management.php"><i class="fas fa-utensils"></i> Manage Menu</a>
      <a href="order-history.php"><i class="fas fa-box"></i>Orders History</a>
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

    <section class="dashboard">
      <div class="card" onclick="location.href='view-orders.php'">
        <div>
          <h3>View Order Submissions</h3>
          <p>Manage and track all orders submitted by customers.</p>
        </div>
        <i class="fas fa-receipt"></i>
      </div>

      <div class="card" onclick="location.href='menu-management.php'">
        <div>
          <h3>Menu Management</h3>
          <p>Update, add or remove products on the website menu.</p>
        </div>
        <i class="fas fa-utensils"></i>
      </div>

      <div class="card" onclick="location.href='order-history.php'">
        <div>
          <h3>Orders History</h3>
          <p>View all completed and rejected customer orders.</p>
        </div>
        <i class="fas fa-box"></i>
      </div>

      <div class="card" onclick="location.href='messages.php'">
        <div>
          <h3>Messages</h3>
          <p>Check and respond to customer inquiries.</p>
        </div>
        <i class="fas fa-envelope"></i>
      </div>

      <div class="card" onclick="location.href='customers.php'">
        <div>
          <h3>Customer Data</h3>
          <p>View registered customer details.</p>
        </div>
        <i class="fas fa-users"></i>
      </div>
    </section>

    <footer>
      &copy; 2025 Dhen's Kitchen. All rights reserved.
    </footer>
  </div>
</body>
</html>

