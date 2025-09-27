<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Doctor Login';
if (dd_is_logged_in()) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($username && $password && dd_login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Invalid username or password';
}
include __DIR__ . '/includes/header.php';
?>
<div class="container py-5">
  <div class="mx-auto dd-card" style="max-width:460px;">
    <h3 class="mb-3">Sign in</h3>
    <?php if ($error): ?>
      <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" id="email" name="email" type="email" required />
      </div>
      <div class="mb-3">
        <label class="form-label" for="password">Password</label>
        <input class="form-control" id="password" name="password" type="password" required />
      </div>
      <button class="btn btn-primary" type="submit">Login</button>
      <a class="btn btn-secondary ms-2" href="../home/first.html">Back to Home</a>
    </form>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
