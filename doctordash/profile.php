<?php
require_once __DIR__ . '/includes/bootstrap.php';
dd_require_login();
$doctor = dd_current_doctor();
$page_title = 'Profile';
$profile = dd_get_profile($doctor['id']);
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? $doctor['name']);
    $email = trim($_POST['email'] ?? $doctor['email']);
    $specialty = trim($_POST['specialty'] ?? $doctor['specialty']);
    $phone = trim($_POST['phone'] ?? '');
    $clinic = trim($_POST['clinic'] ?? '');
    $degree = trim($_POST['degree'] ?? '');
    $bio = trim($_POST['bio'] ?? '');

    // Save detailed profile
    $profile = [
        'name' => $name,
        'email' => $email,
        'specialty' => $specialty,
        'contact_phone' => $phone,
        'clinic' => $clinic,
        'degree' => $degree,
        'bio' => $bio,
    ];
    dd_save_profile($doctor['id'], $profile);

    // Keep session basic fields in sync
    $_SESSION['doctor']['name'] = $name;
    $_SESSION['doctor']['email'] = $email;
    $_SESSION['doctor']['specialty'] = $specialty;

    $notice = 'Profile updated successfully';
}

include __DIR__ . '/includes/header.php';
?>
<div class="dd-container">
  <aside class="dd-sidebar">
    <h2>Doctor Panel</h2>
    <ul>
      <li><a href="dashboard.php"><i class="fa fa-home me-2"></i>Dashboard</a></li>
      <li><a class="active" href="profile.php"><i class="fa fa-user me-2"></i>Profile</a></li>
      <li><a href="appointments.php"><i class="fa fa-calendar-check me-2"></i>Appointments</a></li>
      <li><a href="availability.php"><i class="fa fa-clock me-2"></i>Availability</a></li>
      <li><a href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
    </ul>
  </aside>
  <main class="dd-main">
    <div class="dd-card mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Profile</h4>
        <span class="text-muted">Username: <?php echo htmlspecialchars($doctor['username']); ?></span>
      </div>
    </div>

    <?php if ($notice): ?>
      <div class="alert alert-success dd-card py-2"><?php echo htmlspecialchars($notice); ?></div>
    <?php endif; ?>

    <div class="dd-card">
      <form method="post">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input class="form-control" name="name" value="<?php echo htmlspecialchars($profile['name'] ?? $doctor['name']); ?>" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? $doctor['email']); ?>" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Specialty</label>
            <input class="form-control" name="specialty" value="<?php echo htmlspecialchars($profile['specialty'] ?? $doctor['specialty']); ?>" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input class="form-control" name="phone" value="<?php echo htmlspecialchars($profile['contact_phone'] ?? ''); ?>" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Clinic / Location</label>
            <input class="form-control" name="clinic" value="<?php echo htmlspecialchars($profile['clinic'] ?? ''); ?>" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Degree / Qualification</label>
            <input class="form-control" name="degree" value="<?php echo htmlspecialchars($profile['degree'] ?? ''); ?>" />
          </div>
          <div class="col-12">
            <label class="form-label">Bio</label>
            <textarea class="form-control" name="bio" rows="4" placeholder="Short professional bio..."><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
          </div>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" type="submit">Save Changes</button>
          <a class="btn btn-secondary ms-2" href="dashboard.php">Back</a>
        </div>
      </form>
    </div>
  </main>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
