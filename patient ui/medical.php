<?php
require_once __DIR__ . '/includes/bootstrap.php';
pui_require_login();
$page_title = 'Medical Records';
include __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="card">
    <h2 style="margin:0 0 12px;">Medical Records</h2>
    <p>This is a placeholder records page.</p>
    <p><a href="patientui.php">Back to Dashboard</a></p>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; exit; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Medical Records</title>

  <!-- Link to CSS -->
  <link rel="stylesheet" href="medical-records.css" />
</head>
<body>
  <div class="container">
    <h1>Medical Records</h1>

    <input type="text" id="search" placeholder="Search records by condition, doctor, or date...">

    <div class="records">
      <div class="record">
        <div class="record-summary">
          <span>Date: 2025-08-15</span>
          <span>Condition: Hypertension</span>
          <button class="toggle-details">Details</button>
        </div>
        <div class="record-details">
          <p><strong>Doctor:</strong> Dr. Smith</p>
          <p><strong>Medications:</strong> Lisinopril 10mg</p>
          <p><strong>Notes:</strong> Blood pressure improved. Continue medication.</p>
        </div>
      </div>

      <div class="record">
        <div class="record-summary">
          <span>Date: 2025-06-30</span>
          <span>Condition: Diabetes</span>
          <button class="toggle-details">Details</button>
        </div>
        <div class="record-details">
          <p><strong>Doctor:</strong> Dr. Johnson</p>
          <p><strong>Medications:</strong> Metformin 500mg</p>
          <p><strong>Notes:</strong> Advised on diet control and regular testing.</p>
        </div>
      </div>

      <!-- More records can go here -->
    </div>
  </div>

  <!-- Link to JS -->
  <script src="medical-records.js"></script>
  <style>
    /* Reset */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Segoe UI', sans-serif;
  background: #f8fafc;
  color: #333;
  padding: 20px;
}

.container {
  max-width: 900px;
  margin: auto;
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
}

h1 {
  margin-bottom: 25px;
  text-align: center;
  color: #2c3e50;
}

#search {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 25px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 16px;
}

.record {
  border: 1px solid #ddd;
  border-radius: 8px;
  margin-bottom: 20px;
  overflow: hidden;
}

.record-summary {
  background: #f1f3f6;
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.record-summary span {
  font-weight: 500;
  margin-right: 10px;
}

.toggle-details {
  background: #3498db;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.toggle-details:hover {
  background: #2980b9;
}

.record-details {
  display: none;
  padding: 15px 20px;
  background: #fff;
  border-top: 1px solid #ddd;
}

.record-details p {
  margin-bottom: 10px;
  font-size: 15px;
}

/* Responsive */
@media (max-width: 600px) {
  .record-summary {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .toggle-details {
    width: 100%;
  }
}

  </style>
  <script>
    // Toggle details visibility
document.querySelectorAll('.toggle-details').forEach(button => {
  button.addEventListener('click', () => {
    const details = button.closest('.record').querySelector('.record-details');
    details.style.display = (details.style.display === 'block') ? 'none' : 'block';
  });
});

// Filter records based on search input
document.getElementById('search').addEventListener('input', function () {
  const query = this.value.toLowerCase();
  const records = document.querySelectorAll('.record');

  records.forEach(record => {
    const text = record.innerText.toLowerCase();
    record.style.display = text.includes(query) ? 'block' : 'none';
  });
});

  </script>
</body>
</html>