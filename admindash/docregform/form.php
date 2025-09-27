<?php
require_once __DIR__ . '/../auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Doctor - CareGroup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <a href="../index.php" class="btn btn-link mb-3"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
        <div class="card shadow-sm">
          <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user-md me-2"></i>Add Doctor</h5>
          </div>
          <div class="card-body">
            <form method="post" action="procesdoc.php">
              <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Specialization</label>
                <select name="specialization" class="form-select" required>
                  <option value="">Select specialization</option>
                  <option>Cardiology</option>
                  <option>Neurology</option>
                  <option>Pediatrics</option>
                  <option>Orthopedics</option>
                  <option>Dermatology</option>
                  <option>General Medicine</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Email (optional)</label>
                <input type="email" name="email" class="form-control">
              </div>
              <div class="mb-3">
                <label class="form-label">Phone (optional)</label>
                <input type="tel" name="phone" class="form-control">
              </div>
              <!-- Location and License removed as requested -->
              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Add Doctor</button>
                <a href="../index.php" class="btn btn-secondary">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
