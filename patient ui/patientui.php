<?php
require_once __DIR__ . '/includes/bootstrap.php';
pui_require_login();
$page_title = 'Patient Dashboard';
include __DIR__ . '/includes/header.php';
?>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>Patient Dashboard</h2>
      <ul>
        <li><a href="patientui.php">Dashboard</a></li>
        <li><a href="medical.php">Medical Records</a></li>
        <li><a href="message.php">Messages</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Header -->
      <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars(pui_user_name()); ?></h1>
        <div class="user-info">
          <span><?php echo htmlspecialchars(pui_user_email()); ?></span>
        </div>
      </div>

      <!-- Cards -->
      <div class="cards">
        <div class="card">
          <h3>Next Appointment</h3>
          <p>—</p>
        </div>
        <div class="card">
          <h3>Medical Summary</h3>
          <p>—</p>
        </div>
        <div class="card">
          <h3>Prescriptions</h3>
          <p>—</p>
        </div>
        <div class="card">
          <h3>Recent Lab Results</h3>
          <p>—</p>
        </div>
      </div>
    </main>
  </div>
<?php include __DIR__ . '/includes/footer.php'; exit; ?>
<?php
require_once __DIR__ . '/includes/bootstrap.php';
pui_require_login();
$page_title = 'Patient Dashboard';
include __DIR__ . '/includes/header.php';
?>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>Patient Dashboard</h2>
      <ul>
        <li><a href="patientui.php">Dashboard</a></li>
        <li><a href="medical.php">Medical Records</a></li>
        <li><a href="message.php">Messages</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Header -->
      <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars(pui_user_name()); ?></h1>
        <div class="user-info">
          <span><?php echo htmlspecialchars(pui_user_email()); ?></span>
        </div>
      </div>

      <!-- Cards -->
      <div class="cards">
        <div class="card">
          <h3>Next Appointment</h3>
          <p>—</p>
        </div>
        <div class="card">
          <h3>Medical Summary</h3>
          <p>—</p>
        </div>
        <div class="card">
          <h3>Prescriptions</h3>
          <p>—</p>
        </div>
        <div class="card">
          <h3>Recent Lab Results</h3>
          <p>—</p>
        </div>
      </div>
    </main>
  </div>
<?php include __DIR__ . '/includes/footer.php'; exit; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Dashboard</title>
<link rel="Appoinment.php" href="Appoinment.php"/>

  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6fc;
      color: #333;
    }

    /* Layout */
    .dashboard {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      background: #2c3e50;
      color: #fff;
      width: 250px;
      padding: 30px 20px;
      flex-shrink: 0;
    }

    .sidebar h2 {
      font-size: 24px;
      margin-bottom: 40px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      margin: 20px 0;
    }

    .sidebar ul li a {
      color: #ecf0f1;
      text-decoration: none;
      font-size: 16px;
      transition: 0.3s;
    }

    .sidebar ul li a:hover {
      color: #1abc9c;
    }

    /* Main */
    .main-content {
      flex: 1;
      padding: 30px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    .header h1 {
      font-size: 28px;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .user-info img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    /* Cards */
    .cards {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .card {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      flex: 1 1 250px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card h3 {
      margin-bottom: 10px;
      font-size: 20px;
      color: #34495e;
    }

    .card p {
      font-size: 14px;
      color: #666;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .dashboard {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        text-align: center;
      }

      .header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
    }

    @media (max-width: 480px) {
      .cards {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>Doctor Dashboard</h2>
      <ul>
        <li><a href="#">Dashboard</a></li>
        <li><a href="Appoinment.php">Appointments</a></li>
        <li><a href="medical.php">Medical Records</a></li>
        <li><a href="message.php">Messages</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Header -->
      <div class="header">
        <h1>Welcome, Syed Muhammad Ali</h1>
        <div class="user-info">
          <span>syedmuhammadali@gmail.com</span>
        </div>
      </div>

      <!-- Cards -->
      <div class="cards">
        <div class="card">
          <h3>Next Appointment</h3>
          <p>Sept 28, 2025 - 10:00 AM</p>
          <p>With Dr. Smith (Cardiologist)</p>
        </div>
        <div class="card">
          <h3>Medical Summary</h3>
          <p>Blood Pressure: 120/80</p>
          <p>Heart Rate: 72 bpm</p>
        </div>
        <div class="card">
          <h3>Prescriptions</h3>
          <p>Atorvastatin - 10mg</p>
          <p>Take once daily</p>
        </div>
        <div class="card">
          <h3>Recent Lab Results</h3>
          <p>Cholesterol: Normal</p>
          <p>Glucose: Slightly High</p>
        </div>
      </div>
    </main>
  </div>
</body>
</html>