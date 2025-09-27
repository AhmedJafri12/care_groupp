<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';
require_login();

$pdo = db();
$doctors = [];
try {
    $stmt = $pdo->query('SELECT id, name, specialization, email, phone, status, created_at FROM doctors ORDER BY id DESC');
    $doctors = $stmt->fetchAll();
} catch (Throwable $e) {
    $error = 'Failed to load doctors.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctors - CareGroup</title>
  <meta name="csrf-token" content="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0"><i class="fas fa-user-md me-2"></i>Doctors</h3>
      <div>
        <a href="form.php" class="btn btn-primary me-2"><i class="fas fa-plus me-2"></i>Add Doctor</a>
        <a href="../index.php" class="btn btn-outline-secondary"><i class="fas fa-home me-2"></i>Dashboard</a>
      </div>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-header">Doctors List</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
              <?php if (!$doctors): ?>
                <tr><td colspan="7" class="text-center text-muted">No doctors found.</td></tr>
              <?php else: ?>
                <?php foreach ($doctors as $d): ?>
                  <tr>
                    <td><?php echo (int)$d["id"]; ?></td>
                    <td><?php echo htmlspecialchars($d["name"]); ?></td>
                    <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($d["specialization"]); ?></span></td>
                    <td><?php echo htmlspecialchars($d["email"] ?? ""); ?></td>
                    <td><?php echo htmlspecialchars($d["phone"] ?? ""); ?></td>
                    <td>
                      <?php
                        $status = $d['status'] ?? 'Active';
                        $map = [
                          'Active' => 'success',
                          'Inactive' => 'secondary',
                          'On Leave' => 'warning'
                        ];
                        $cls = $map[$status] ?? 'secondary';
                      ?>
                      <span class="badge bg-<?php echo $cls; ?>"><?php echo htmlspecialchars($status); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($d['created_at']); ?></td>
                    <td>
                      <button class="btn btn-sm btn-primary me-1" onclick="editDoctor(<?php echo (int)$d['id']; ?>)"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="deleteDoctor(<?php echo (int)$d['id']; ?>)"><i class="fas fa-trash"></i></button>
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
  const r = await fetch('../api.php', { method:'POST', body: fd });
  try { return await r.json(); } catch { return { success:false, message:'Invalid response' }; }
}

async function editDoctor(id){
  const name = prompt('New name (leave blank to keep):', '');
  const specialization = prompt('New specialization (leave blank to keep):', '');
  const email = prompt('New email (optional):', '');
  const phone = prompt('New phone (optional):', '');
  const status = prompt("Status (Active/Inactive/'On Leave'):", 'Active') || 'Active';
  const res = await api('doctor.update', { id, name, specialization, email, phone, status });
  alert(res.message || (res.success ? 'Updated' : 'Failed'));
  if (res.success) location.reload();
}

async function deleteDoctor(id){
  if (!confirm('Delete this doctor?')) return;
  const res = await api('doctor.delete', { id });
  alert(res.message || (res.success ? 'Deleted' : 'Failed'));
  if (res.success) location.reload();
}
</script>
</body>
</html>
