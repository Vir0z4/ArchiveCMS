<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - VAIO Library Archive</title>
    <link rel="stylesheet" href="/static/style_login.css">
</head>
<body>
  <nav>
    <div class="nav-container">
        <div class="logo">
            <img src="/static/logo.png" alt="VAIO Library Logo">
        </div>
        <div class="right-side">
          <div class="menu">
              <p>ADMINISTRATION</p>
          </div>
      </div>
    </div>
  </nav>

  <main>
      <div class="center-container">
          <div class="title">
              <h1>Login</h1>
          </div>

          <div class="loginform">
            <form method="POST" action="/login">
              <!-- CSRF token -->
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

              <input type="text" name="username" placeholder="Username" required>
              <input type="password" name="password" placeholder="Password" required>
              <button type="submit">Login</button>
            </form>

            <?php if (!empty($error)): ?>
              <div style="color: red; margin-bottom: 1em;">
                <?= htmlspecialchars($error) ?>
              </div>
            <?php endif; ?>
          </div>
      </div>
  </main>

  <footer>
    <p class="footertext">&copy; 2025 VAIO Library</p>
  </footer>
</body>
</html>