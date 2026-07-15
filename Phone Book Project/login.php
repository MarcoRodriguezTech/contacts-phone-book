<?php
session_start();
require 'db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$activeTab = 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $action = $_POST['action'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
        $activeTab = $action;
    } elseif ($action === 'register') {
        $activeTab = 'register';
        
        // Match verification from login.php
        if ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'That username is already taken.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
                $stmt->execute([$username, $hash]);
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header('Location: dashboard.php');
                exit;
            }
        }
    } elseif ($action === 'login') {
        $activeTab = 'login';
        $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WhoYou PH — Authentication</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="icons/web-icon.png">
<link rel="stylesheet" href="style.css">

<script>
  (function() {
    const savedTheme = localStorage.getItem('phonebook-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (prefersDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
  })();
</script>
</head>
<body class="auth-body">
  <div class="auth-card">
    <h1> 
      <img src="icons/web-icon.png" alt="Phone Book Logo" class="brand-logo">
      WhoYou PH
    </h1>
    
    <div class="tabs">
      <button type="button" class="tab-btn <?= $activeTab === 'login' ? 'active' : '' ?>" data-tab="login">Login</button>
      <button type="button" class="tab-btn <?= $activeTab === 'register' ? 'active' : '' ?>" data-tab="register">Register</button>
    </div>

    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" id="login-form" class="auth-form <?= $activeTab === 'login' ? '' : 'hidden' ?>">
      <input type="hidden" name="action" value="login">
      <label>
        Username 
        <input type="text" name="username" required autofocus value="<?= htmlspecialchars($username ?? '') ?>">
      </label>
      <label>
        Password 
        <span class="password-container">
          <input type="password" name="password" class="password-field" required>
          <button type="button" class="toggle-password" aria-label="Show password" aria-pressed="false" style="display: none;">
            <svg class="icon-eye" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/>
            </svg>
            <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M3 3l18 18M10.6 5.2A10.9 10.9 0 0 1 12 5c7 0 10.5 7 10.5 7a13.5 13.5 0 0 1-3.1 4.1M6.6 6.6C3.7 8.4 1.5 12 1.5 12s3.5 7 10.5 7c1.3 0 2.5-.2 3.6-.6M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </span>
      </label>
      <button type="submit">Log In</button>
    </form>

    <form method="post" id="register-form" class="auth-form <?= $activeTab === 'register' ? '' : 'hidden' ?>">
      <input type="hidden" name="action" value="register">
      <label>
        Username 
        <input type="text" name="username" required value="<?= htmlspecialchars($username ?? '') ?>">
      </label>
      <label>
        Password 
        <span class="password-container">
          <input type="password" name="password" class="password-field" required minlength="4">
          <button type="button" class="toggle-password" aria-label="Show password" aria-pressed="false" style="display: none;">
            <svg class="icon-eye" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/>
            </svg>
            <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M3 3l18 18M10.6 5.2A10.9 10.9 0 0 1 12 5c7 0 10.5 7 10.5 7a13.5 13.5 0 0 1-3.1 4.1M6.6 6.6C3.7 8.4 1.5 12 1.5 12s3.5 7 10.5 7c1.3 0 2.5-.2 3.6-.6M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </span>
      </label>
      <label>
        Confirm Password 
        <span class="password-container">
          <input type="password" name="confirm_password" class="password-field" required minlength="4">
          <button type="button" class="toggle-password" aria-label="Show password" aria-pressed="false" style="display: none;">
            <svg class="icon-eye" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.6"/>
            </svg>
            <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M3 3l18 18M10.6 5.2A10.9 10.9 0 0 1 12 5c7 0 10.5 7 10.5 7a13.5 13.5 0 0 1-3.1 4.1M6.6 6.6C3.7 8.4 1.5 12 1.5 12s3.5 7 10.5 7c1.3 0 2.5-.2 3.6-.6M9.9 9.9a3 3 0 0 0 4.2 4.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </span>
      </label>
      <button type="submit">Create Account</button>
    </form>
  </div>

<script>
// Tab toggling logic
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.auth-form').forEach(f => f.classList.add('hidden'));
    btn.classList.add('active');
    document.getElementById(btn.dataset.tab + '-form').classList.remove('hidden');
  });
});

// Interactive SVG eye toggle + dynamic observer
document.querySelectorAll('.password-field').forEach(input => {
  const toggleBtn = input.parentElement.querySelector('.toggle-password');

  // Monitor typing events to show/hide the eye icon
  input.addEventListener('input', () => {
    if (input.value.trim().length > 0) {
      toggleBtn.style.display = 'block'; // Show eye if there's text
    } else {
      toggleBtn.style.display = 'none';  // Hide eye if empty
    }
  });

  // Handle clicking the eye icon to transition SVG states
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const showing = input.type === 'text';
      input.type = showing ? 'password' : 'text';
      toggleBtn.classList.toggle('is-visible', !showing);
      toggleBtn.setAttribute('aria-pressed', String(!showing));
      toggleBtn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
    });
  }
});
</script>
</body>
</html>