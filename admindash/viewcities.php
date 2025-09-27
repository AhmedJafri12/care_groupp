<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_login();

$pdo = db();
$cities = [];
try {
    $stmt = $pdo->query('SELECT c.id, c.name, c.created_at, COUNT(p.id) AS patients_count FROM cities c LEFT JOIN patients p ON p.city_id = c.id GROUP BY c.id, c.name, c.created_at ORDER BY c.id DESC');
    $cities = $stmt->fetchAll();
} catch (Throwable $e) {
    $error = 'Failed to load cities.';
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cities - CareGroup</title>
  <meta name="csrf-token" content="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0"><i class="fas fa-city me-2"></i>Cities</h3>
      <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-home me-2"></i>Dashboard</a>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-header">Cities List</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>City</th>
                <th>Patients</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$cities): ?>
                <tr><td colspan="4" class="text-center text-muted">No cities found.</td></tr>
              <?php else: ?>
                <?php foreach ($cities as $c): ?>
                  <tr>
                    <td><?php echo (int)$c['id']; ?></td>
                    <td><?php echo htmlspecialchars($c['name']); ?></td>
                    <td><span class="badge bg-primary"><?php echo (int)$c['patients_count']; ?></span></td>
                    <td><?php echo htmlspecialchars($c['created_at']); ?></td>
                    <td>
                      <button class="btn btn-sm btn-danger" onclick="deleteCity(<?php echo (int)$c['id']; ?>)"><i class="fas fa-trash"></i></button>
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

async function deleteCity(id){
  if (!confirm('Delete this city?')) return;
  const res = await api('city.delete', { id });
  alert(res.message || (res.success ? 'Deleted' : 'Failed'));
  if (res.success) location.reload();
}
</script>
</body>
</html>
