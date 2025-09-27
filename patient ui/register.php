<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Patient Registration';

if (pui_is_logged_in()) {
    header('Location: patientui.php');
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name !== '' && $email !== '' && $contact !== '' && $password !== '') {
        try {
            $user = pui_register_user($name, $email, $contact, $password);
            // Set session from created user
            $_SESSION['pui_user'] = [
                'name' => $user['name'],
                'email' => $user['email'],
                'contact' => $user['contact'],
                'id' => $user['id'],
            ];
            header('Location: patientui.php');
            exit;
        } catch (Throwable $e) {
            $err = $e->getMessage();
        }
    } else {
        $err = 'Please enter name, email, contact and password.';
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="card" style="max-width:560px;margin:48px auto;">
    <h2 style="margin:0 0 16px;">Create your account</h2>
    <?php if ($err): ?>
      <div style="background:#ffe3e3;border:1px solid #f5c2c2;padding:10px;border-radius:8px;margin-bottom:12px;color:#b02a37;">
        <?php echo htmlspecialchars($err); ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <div style="margin-bottom:12px;">
        <label for="name">Full Name</label><br>
        <input id="name" name="name" type="text" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;" />
      </div>
      <div style="margin-bottom:12px;">
        <label for="email">Email</label><br>
        <input id="email" name="email" type="email" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;" />
      </div>
      <div style="margin-bottom:12px;">
        <label for="contact">Contact</label><br>
        <input id="contact" name="contact" type="text" required placeholder="e.g., +92 300 1234567" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;" />
      </div>
      <div style="margin-bottom:16px;">
        <label for="password">Password</label><br>
        <input id="password" name="password" type="password" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;" />
      </div>
      <button class="btn" type="submit">Register</button>
      <p style="text-align:center;margin-top:12px;">Already have an account? <a href="login.php">Sign in</a></p>
    </form>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
