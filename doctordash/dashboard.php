<?php
require_once __DIR__ . '/includes/bootstrap.php';
dd_require_login();
$page_title = 'Dashboard';
$doctor = dd_current_doctor();
include __DIR__ . '/includes/header.php';
?>
<div class="dd-container">
  <aside class="dd-sidebar">
    <h2>Doctor Panel</h2>
    <ul>
      <li><a class="active" href="dashboard.php"><i class="fa fa-home me-2"></i>Dashboard</a></li>
      <li><a href="profile.php"><i class="fa fa-user me-2"></i>Profile</a></li>
      <li><a href="appointments.php"><i class="fa fa-calendar-check me-2"></i>Appointments</a></li>
      <li><a href="availability.php"><i class="fa fa-clock me-2"></i>Availability</a></li>
      <li><a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
    </ul>
  </aside>
  <main class="dd-main">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0">Welcome, <?php echo htmlspecialchars($doctor['name']); ?></h3>
      <span class="text-muted"><?php echo htmlspecialchars($doctor['email']); ?> • <?php echo htmlspecialchars($doctor['specialty']); ?></span>
    </div>

    <div class="dd-cards mb-4">
      <div class="dd-card">
        <h5 class="mb-1">Today</h5>
        <p class="text-muted mb-2">Quick overview</p>
        <ul class="mb-0">
          <li>Upcoming appointments: <strong><?php echo count(dd_get_appointments($doctor['id'])); ?></strong></li>
        </ul>
      </div>
      <div class="dd-card">
        <h5 class="mb-1">Profile</h5>
        <p class="text-muted mb-2">Update your details</p>
        <a class="btn btn-primary btn-sm" href="profile.php">Edit Profile</a>
      </div>
      <div class="dd-card">
        <h5 class="mb-1">Availability</h5>
        <p class="text-muted mb-2">Day / Week / Month</p>
        <a class="btn btn-primary btn-sm" href="availability.php">Manage Slots</a>
      </div>
    </div>

    <div class="dd-card">
      <h5 class="mb-3">Next Appointments</h5>
      <?php $appts = dd_get_appointments($doctor['id']); if (!$appts): ?>
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
