<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_login();

$pdo = db();
$patients = [];
try {
    $stmt = $pdo->query('SELECT p.id, p.name, p.email, p.phone, p.created_at, c.name AS city_name FROM patients p LEFT JOIN cities c ON p.city_id = c.id ORDER BY p.id DESC');
    $patients = $stmt->fetchAll();
} catch (Throwable $e) {
    $error = 'Failed to load patients.';
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patients - CareGroup</title>
  <meta name="csrf-token" content="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0"><i class="fas fa-procedures me-2"></i>Patients</h3>
      <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-home me-2"></i>Dashboard</a>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-header">Patients List</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>City</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$patients): ?>
                <tr><td colspan="6" class="text-center text-muted">No patients found.</td></tr>
              <?php else: ?>
                <?php foreach ($patients as $p): ?>
                  <tr>
                    <td><?php echo (int)$p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                    <td><?php echo htmlspecialchars($p['email'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($p['phone'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($p['city_name'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($p['created_at']); ?></td>
                    <td>
                      <button class="btn btn-sm btn-primary me-1" onclick="editPatient(<?php echo (int)$p['id']; ?>)"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="deletePatient(<?php echo (int)$p['id']; ?>)"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script>
async function api(action, data={}){
  const fd = new FormData();
  fd.append('action', action);
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fd.append('csrf_token', csrf);
  Object.entries(data).forEach(([k,v])=>fd.append(k, v));
  const r = await fetch('api.php', { method:'POST', body: fd });
  try { return await r.json(); } catch { return { success:false, message:'Invalid response' }; }
}

async function editPatient(id){
  const name = prompt('New name (leave blank to keep):', '');
  const email = prompt('New email (optional):', '');
  const phone = prompt('New phone (optional):', '');
  const city_id = prompt('City ID (optional):', '');
  const res = await api('patient.update', { id, name, email, phone, city_id });
  alert(res.message || (res.success ? 'Updated' : 'Failed'));
  if (res.success) location.reload();
}

async function deletePatient(id){
  if (!confirm('Delete this patient?')) return;
  const res = await api('patient.delete', { id });
  alert(res.message || (res.success ? 'Deleted' : 'Failed'));
  if (res.success) location.reload();
}
</script>
</body>
</html>
