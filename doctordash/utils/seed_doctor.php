<?php
// Temporary utility to create a doctor with a hashed password.
// Remove this file after seeding your initial accounts.
require_once __DIR__ . '/../../config/db.php';

$info = '';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $specialty = trim($_POST['specialty'] ?? '');
    $contact = trim($_POST['contact_phone'] ?? '');

    if ($name && $email && $password) {
        try {
            $pdo = db();
            // Ensure email unique
            $stmt = $pdo->prepare('SELECT id FROM doctors WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) throw new RuntimeException('Email already exists');

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare('INSERT INTO doctors (name, email, contact_phone, password_hash, specialty) VALUES (?,?,?,?,?)');
            $ins->execute([$name, $email, $contact, $hash, $specialty]);
            $info = 'Doctor created with ID: ' . (int)$pdo->lastInsertId();
        } catch (Throwable $e) {
            $err = $e->getMessage();
        }
    } else {
        $err = 'Name, Email and Password are required';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Seed Doctor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container" style="max-width:640px;">
    <div class="card p-4">
      <h3 class="mb-3">Create Doctor (Temporary)</h3>
      <?php if ($info): ?><div class="alert alert-success"><?php echo htmlspecialchars($info); ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input class="form-control" name="name" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" type="email" name="email" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" required />
        </div>
        <div class="mb-3">
          <label class="form-label">Specialty</label>
          <input class="form-control" name="specialty" />
        </div>
        <div class="mb-3">
          <label class="form-label">Contact Phone</label>
          <input class="form-control" name="contact_phone" />
        </div>
        <button class="btn btn-primary" type="submit">Create Doctor</button>
      </form>
      <p class="text-muted mt-3">Note: Delete this file after use for security.</p>
    </div>
  </div>
</body>
</html>
