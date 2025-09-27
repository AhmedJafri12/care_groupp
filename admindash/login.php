<?php
require_once __DIR__ . '/auth.php';
$page_title = 'Login - CareGroup';

if (isLoggedIn()) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root{--primary-color:#4361ee;--border-radius:12px;--white:#fff;--shadow-lg:0 15px 35px rgba(50,50,93,.1);--text-dark:#212529;--text-light:#6c757d;--border-color:#e9ecef}
        body{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
        .auth-wrapper{background:var(--white);padding:3rem;border-radius:var(--border-radius);box-shadow:var(--shadow-lg);width:100%;max-width:450px}
        .auth-title{color:var(--text-dark)}
        .auth-form .form-group{margin-bottom:1.2rem}
        .password-toggle{position:relative}
        .password-toggle input{padding-right:40px}
        .password-toggle i{position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--text-light)}
        .auth-links{ text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border-color)}
        .modal{display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5);}
        .modal-content{background:#fff;margin:10% auto;padding:20px;border-radius:12px;max-width:480px}
        .modal-header{display:flex;justify-content:space-between;align-items:center}
        .close{cursor:pointer}
    </style>
</head>
<body>
<section class="auth-container">
    <div class="auth-wrapper">
        <div class="text-center mb-4">
            <div class="logo mb-3">
                <i class="fas fa-sign-in-alt" style="font-size: 3rem; color: var(--primary-color);"></i>
            </div>
            <h2 class="auth-title">Welcome Back</h2>
            <p class="text-muted">Sign in to your CareGroup account</p>
        </div>
        
        <form id="login-form" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
            <div class="form-group">
                <label for="login_email">Email or Username</label>
                <input type="text" id="login_email" name="email" placeholder="Enter your email or username" required autocomplete="username" class="form-control">
            </div>
            <div class="form-group password-toggle">
                <label for="login_password">Password</label>
                <div class="position-relative">
                    <input type="password" id="login_password" name="password" placeholder="Enter your password" required autocomplete="current-password" class="form-control">
                    <i class="fas fa-eye-slash" id="toggleLoginPassword"></i>
                </div>
            </div>
            <div class="form-group form-check mb-4">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt me-2"></i>Sign In</button>
        </form>
        <div class="auth-links">
            <p class="mb-0">Don't have an account?</p>
            <a href="#" onclick="alert('Sign up is disabled for admin portal');return false;">Create a new account</a>
        </div>
    </div>
</section>


<script>
// Password toggle
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

// Password reset removed

// Handle login submit
const form = document.getElementById('login-form');
form.addEventListener('submit', async function(e){
  e.preventDefault();
  const fd = new FormData(form);
  const btn = form.querySelector('button[type="submit"]');
  const old = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...'; btn.disabled = true;
  try {
    const r = await fetch('api/login.php', { method: 'POST', body: fd, credentials: 'same-origin' });
    const j = await r.json();
    if (j.success) { window.location.href = 'index.php'; }
    else { alert(j.message || 'Invalid credentials'); }
  } catch(_) {
    alert('Network error');
  } finally {
    btn.innerHTML = old; btn.disabled = false;
  }
});
</script>
</body>
</html>
