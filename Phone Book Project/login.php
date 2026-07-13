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
<title>Phone Book — Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
  <div class="auth-card">
    <h1>📇 Phone Book</h1>
    <div class="tabs">
      <button type="button" class="tab-btn <?= $activeTab === 'login' ? 'active' : '' ?>" data-tab="login">Login</button>
      <button type="button" class="tab-btn <?= $activeTab === 'register' ? 'active' : '' ?>" data-tab="register">Register</button>
    </div>

    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" id="login-form" class="auth-form <?= $activeTab === 'login' ? '' : 'hidden' ?>">
      <input type="hidden" name="action" value="login">
      <label>Username <input type="text" name="username" required autofocus></label>
      
      <label>Password 
        <div class="password-container">
          <input type="password" name="password" class="password-field" required>
          <button type="button" class="toggle-password">👁️</button>
        </div>
      </label>
      
      <button type="submit">Log In</button>
    </form>

    <form method="post" id="register-form" class="auth-form <?= $activeTab === 'register' ? '' : 'hidden' ?>">
      <input type="hidden" name="action" value="register">
      <label>Username <input type="text" name="username" required></label>
      
      <label>Password 
        <div class="password-container">
          <input type="password" name="password" class="password-field" required minlength="4">
          <button type="button" class="toggle-password">👁️</button>
        </div>
      </label>
      
      <label>Confirm Password 
        <div class="password-container">
          <input type="password" name="confirm_password" class="password-field" required minlength="4">
          <button type="button" class="toggle-password">👁️</button>
        </div>
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

// Interactive eye icon functionality + dynamic display check
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

  // Handle clicking the eye icon to show/hide password characters
  if (toggleBtn) {
    toggleBtn.addEventListener('click', function() {
      if (input.type === 'password') {
        input.type = 'text';
        this.textContent = '🙈';
      } else {
        input.type = 'password';
        this.textContent = '👁️';
      }
    });
  }
});
</script>
</body>
</html>