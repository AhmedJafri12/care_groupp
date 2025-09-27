<?php
require_once __DIR__ . '/includes/bootstrap.php';
pui_require_login();
$page_title = 'Messages';
include __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="card">
    <h2 style="margin:0 0 12px;">Messages</h2>
    <p>This is a placeholder messages page.</p>
    <p><a href="patientui.php">Back to Dashboard</a></p>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Responsive File Upload Message</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      background: #f4f6f8;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .upload-container {
      background: #fff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 90%;
      text-align: center;
    }

    .upload-container h2 {
      margin-bottom: 1rem;
    }

    .drop-zone {
      border: 2px dashed #007BFF;
      border-radius: 6px;
      padding: 2rem;
      cursor: pointer;
      transition: background 0.3s;
      background: #f9f9f9;
    }

    .drop-zone.dragover {
      background: #e0f0ff;
    }

    .drop-zone p {
      margin: 0;
      color: #555;
    }

    .file-input {
      display: none;
    }

    .status-message {
      margin-top: 1rem;
      padding: 0.5rem;
      border-radius: 5px;
      font-size: 0.95rem;
      display: none;
    }

    .status-success {
      background: #d4edda;
      color: #155724;
    }

    .status-error {
      background: #f8d7da;
      color: #721c24;
    }

    @media (max-width: 480px) {
      .upload-container {
        padding: 1.5rem;
      }

      .drop-zone {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

<div class="upload-container">
  <h2>Upload File</h2>
  <div class="drop-zone" id="dropZone">
    <p>Drag & Drop your file here or click to upload</p>
    <input type="file" id="fileInput" class="file-input" />
  </div>
  <div class="status-message" id="statusMessage"></div>
</div>

<script>
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');
  const statusMessage = document.getElementById('statusMessage');

  // Settings
  const MAX_FILE_SIZE_MB = 5;
  const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];

  // Event listeners
  dropZone.addEventListener('click', () => fileInput.click());

  dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
  });

  dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragover');
  });

  dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    handleFile(file);
  });

  fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    handleFile(file);
  });

  function handleFile(file) {
    if (!file) return;

    const fileSizeMB = file.size / (1024 * 1024);

    if (!ALLOWED_TYPES.includes(file.type)) {
      showStatus('Only JPG, PNG, and PDF files are allowed.', 'error');
      return;
    }

    if (fileSizeMB > MAX_FILE_SIZE_MB) {
      showStatus(`File is too large. Max size is ${MAX_FILE_SIZE_MB}MB.`, 'error');
      return;
    }

    // Simulate upload
    showStatus(`Uploading "${file.name}"...`, 'success');

    setTimeout(() => {
      showStatus(`File "${file.name}" uploaded successfully!`, 'success');
    }, 1500);
  }

  function showStatus(message, type) {
    statusMessage.textContent = message;
    statusMessage.style.display = 'block';
    statusMessage.className = `status-message status-${type}`;
  }
</script>

</body>
</html>