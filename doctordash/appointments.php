<?php
require_once __DIR__ . '/includes/bootstrap.php';
dd_require_login();
$doctor = dd_current_doctor();
$page_title = 'Appointments';
$appts = dd_get_appointments($doctor['id']);
include __DIR__ . '/includes/header.php';
?>
<div class="dd-container">
  <aside class="dd-sidebar">
    <h2>Doctor Panel</h2>
    <ul>
      <li><a href="dashboard.php"><i class="fa fa-home me-2"></i>Dashboard</a></li>
      <li><a href="profile.php"><i class="fa fa-user me-2"></i>Profile</a></li>
      <li><a class="active" href="appointments.php"><i class="fa fa-calendar-check me-2"></i>Appointments</a></li>
      <li><a href="availability.php"><i class="fa fa-clock me-2"></i>Availability</a></li>
      <li><a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
    </ul>
  </aside>
  <main class="dd-main">
    <div class="dd-card mb-3">
      <h4 class="mb-0">Appointments</h4>
    </div>

    <div class="dd-card">
      <?php if (!$appts): ?>
        <p class="text-muted mb-0">No appointments scheduled.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead><tr><th>#</th><th>Patient</th><th>Date & Time</th><th>Reason</th></tr></thead>
            <tbody>
              <?php foreach ($appts as $a): ?>
              <tr>
                <td><?php echo (int)$a['id']; ?></td>
                <td><?php echo htmlspecialchars($a['patient']); ?></td>
                <td><?php echo htmlspecialchars(date('M d, Y H:i', strtotime($a['datetime']))); ?></td>
                <td><?php echo htmlspecialchars($a['reason']); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </main>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
