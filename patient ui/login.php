<?php
require_once __DIR__ . '/includes/bootstrap.php';
$page_title = 'Patient Login';

if (pui_is_logged_in()) {
    header('Location: patientui.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($email !== '' && $password !== '') {
        $user = pui_authenticate($email, $password);
        if ($user) {
            $_SESSION['pui_user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'contact' => $user['contact'] ?? ''
            ];
            header('Location: patientui.php');
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    } else {
        $error = 'Please enter email and password';
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="card" style="max-width:480px;margin:48px auto;">
    <h2 style="margin:0 0 16px;">Sign in</h2>
    <?php if (!empty($error)): ?>
      <div style="background:#ffe3e3;border:1px solid #f5c2c2;padding:10px;border-radius:8px;margin-bottom:12px;color:#b02a37;">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <div style="margin-bottom:12px;">
        <label for="email">Email</label><br>
        <input id="email" name="email" type="email" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;" />
      </div>
      <div style="margin-bottom:16px;">
        <label for="password">Password</label><br>
        <input id="password" name="password" type="password" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;" />
      </div>
      <button class="btn" type="submit">Login</button>
    </form>
    <p style="text-align:center;margin-top:12px;">New to the portal? <a href="register.php">Create an account</a></p>
    <p style="text-align:center;margin-top:6px;">Admin? <a href="../admindash/login.php">Go to Admin Login</a></p>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; exit; ?>

.auth-form .btn {
    padding: 12px;
    font-size: 1rem;
    font-weight: 600;
}

.password-toggle {
    position: relative;
}

.password-toggle input {
    padding-right: 40px;
}

.password-toggle i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: var(--text-light);
    transition: var(--transition);
}

.password-toggle i:hover {
    color: var(--primary-color);
}

.form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-check-input {
    width: auto;
    margin: 0;
}

.auth-links {
    text-align: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.auth-links a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.auth-links a:hover {
    text-decoration: underline;
}

@media (max-width: 480px) {
    .auth-wrapper {
        margin: 1rem;
        padding: 2rem;
    }
    
    .modal-content {
        margin: 1rem;
        width: calc(100% - 2rem);
    }
}
</style>

<script>
// Initialize password toggle
document.addEventListener('DOMContentLoaded', function() {
    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    const loginPassword = document.getElementById('login_password');
    
    if (toggleLoginPassword && loginPassword) {
        toggleLoginPassword.addEventListener('click', function() {
            const type = loginPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            loginPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    }
    
    // Password reset form
    const resetForm = document.getElementById('passwordResetForm');
    if (resetForm) {
        resetForm.addEventListener('submit', handlePasswordReset);
    }
    
    // Close modals on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePasswordReset();
        }
    });
});

function showPasswordReset() {
    document.getElementById('passwordResetModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    document.getElementById('reset_email').focus();
}

function closePasswordReset() {
    document.getElementById('passwordResetModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('passwordResetForm').reset();
}

function handlePasswordReset(e) {
    e.preventDefault();
    
    if (!validateForm('passwordResetForm')) {
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    submitBtn.disabled = true;
    
    fetch('api/password-reset.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Password reset link sent to your email!', 'success');
            closePasswordReset();
        } else {
            showNotification(data.message || 'Failed to send reset link', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('passwordResetModal');
    if (e.target === modal) {
        closePasswordReset();
    }
});
</script>

<?php include 'includes/footer.php'; ?>