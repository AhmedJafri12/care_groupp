<?php
require_once __DIR__ . '/includes/bootstrap.php';
dd_require_login();
$doctor = dd_current_doctor();
$page_title = 'Availability';
$availability = dd_get_availability($doctor['id']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = array_filter(array_map('trim', explode("\n", $_POST['day'] ?? '')));
    $week = array_filter(array_map('trim', explode("\n", $_POST['week'] ?? '')));
    $month = array_filter(array_map('trim', explode("\n", $_POST['month'] ?? '')));
    $availability = [
        'day' => array_values($day),
        'week' => array_values($week),
        'month' => array_values($month),
    ];
    dd_save_availability($doctor['id'], $availability);
    $notice = 'Availability saved';
}

include __DIR__ . '/includes/header.php';
?>
<div class="dd-container">
  <aside class="dd-sidebar">
    <h2>Doctor Panel</h2>
    <ul>
      <li><a href="dashboard.php"><i class="fa fa-home me-2"></i>Dashboard</a></li>
      <li><a href="profile.php"><i class="fa fa-user me-2"></i>Profile</a></li>
      <li><a href="appointments.php"><i class="fa fa-calendar-check me-2"></i>Appointments</a></li>
      <li><a class="active" href="availability.php"><i class="fa fa-clock me-2"></i>Availability</a></li>
      <li><a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
    </ul>
  </aside>
  <main class="dd-main">
    <div class="dd-card mb-3 d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Availability</h4>
      <small class="text-muted">Update for Day / Week / Month</small>
    </div>

    <?php if ($notice): ?>
      <div class="alert alert-success dd-card py-2"><?php echo htmlspecialchars($notice); ?></div>
    <?php endif; ?>

    <div class="dd-card">
      <form method="post">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Day (ISO datetimes, one per line)</label>
            <textarea class="form-control" name="day" rows="4" placeholder="2025-10-01T10:00\n2025-10-01T11:00"><?php echo htmlspecialchars(implode("\n", $availability['day'] ?? [])); ?></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Week (patterns like Mon 10:00-12:00, one per line)</label>
            <textarea class="form-control" name="week" rows="3" placeholder="Mon 10:00-12:00\nWed 14:00-16:00"><?php echo htmlspecialchars(implode("\n", $availability['week'] ?? [])); ?></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">Month (ISO dates, one per line)</label>
            <textarea class="form-control" name="month" rows="3" placeholder="2025-10-05\n2025-10-12"><?php echo htmlspecialchars(implode("\n", $availability['month'] ?? [])); ?></textarea>
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Save Availability</button>
          <a class="btn btn-secondary ms-2" href="dashboard.php">Back</a>
        </div>
      </form>
    </div>
  </main>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
