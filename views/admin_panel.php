<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Administration - VAIO Library Archive</title>
  <link rel="stylesheet" href="/static/style_admin.css">
</head>
<body>
  <nav>
    <div class="nav-container">
      <div class="logo">
        <img src="/static/logo.png" alt="VAIO Library Logo" />
      </div>
      <div class="right-side">
        <div class="menu">
          <p>ADMINISTRATION</p>
        </div>
      </div>
    </div>
  </nav>

  <!-- Tabs -->
  <div class="tabs-nav">
    <div class="tab-button" data-tab="tab-active-pages">Active Pages</div>
    <div class="tab-button" data-tab="tab-inactive-pages">Inactive Pages</div>
    <div class="tab-button" data-tab="tab-users">Users</div>
    <div class="tab-button" data-tab="tab-settings">Settings</div>
    <div class="tab-button" onclick="window.location.href='/logout'">Logout</div>
  </div>

  <main>
    <section id="tab-landing" class="tab-content active">
      <div class="center-container">
        <div class="title">
          <h1>Welcome to the ArchiveCMS Administration Panel</h1>
        </div>
        <p>This panel allows the creation and editing of pages.</p>
      </div>
    </section>

    <!-- Active Pages -->
    <section id="tab-active-pages" class="tab-content">
      <div class="center-container">
        <div class="title">
          <h1>List of Active Pages</h1>
        </div>
        <div class="item">
          <table>
            <thead>
              <tr>
                <th>Page</th>
                <th>Last Modified</th>
                <th>Slug</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($active_pages as $p): ?>
              <tr>
                <td>
                  <a href="/admin/pages/<?= urlencode($p['slug']) ?>/edit">
                    <?= htmlspecialchars($p['title']) ?>
                  </a>
                </td>
                <td><?= htmlspecialchars($p['last_modified']) ?></td>
                <td><?= htmlspecialchars($p['slug']) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
          <button onclick="location.href='/admin/pages/create'">Create Page</button>
        </div>
      </div>
    </section>

    <!-- Inactive Pages -->
    <section id="tab-inactive-pages" class="tab-content">
      <div class="center-container">
        <div class="title">
          <h1>List of Inactive Pages</h1>
        </div>
        <div class="item">
          <table>
            <thead>
              <tr>
                <th>Page</th>
                <th>Last Modified</th>
                <th>Slug</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($inactive_pages as $p): ?>
              <tr>
                <td>
                  <a href="/admin/pages/<?= urlencode($p['slug']) ?>/edit">
                    <?= htmlspecialchars($p['title']) ?>
                  </a>
                </td>
                <td><?= htmlspecialchars($p['last_modified']) ?></td>
                <td><?= htmlspecialchars($p['slug']) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Users -->
    <section id="tab-users" class="tab-content">
      <div class="center-container">
        <div class="title">
          <h1>Manage Users</h1>
        </div>

        <?php
          $currentUser = $_SESSION['username'] ?? '';
          $isAdmin = ($currentUser === 'admin');
        ?>

        <!-- Existing Users -->
        <div class="item">
          <table>
            <thead>
              <tr><th>ID</th><th>Username</th><th>Delete</th></tr>
            </thead>
            <tbody>
              <?php foreach ($users as $u): ?>
              <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td>
                  <?php 
                    // If admin, can delete anyone except itself.
                    if ($isAdmin) {
                      if ($u['username'] === 'admin') {
                        echo '<em>(Cannot delete admin account)</em>';
                      } else {
                        // show delete form
                        ?>
                        <form method="POST" action="/admin" style="display:inline;">
                          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                          <input type="hidden" name="action" value="delete_user">
                          <input type="hidden" name="delete_user_id" value="<?= htmlspecialchars($u['id']) ?>">
                          <button type="submit" onclick="return confirm('Delete <?= htmlspecialchars($u['username']) ?>?');">Delete</button>
                        </form>
                        <?php
                      }
                    } else {
                      // Non-admin => can only delete themselves
                      if ($u['username'] === $currentUser) {
                        ?>
                        <form method="POST" action="/admin" style="display:inline;">
                          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                          <input type="hidden" name="action" value="delete_user">
                          <input type="hidden" name="delete_user_id" value="<?= htmlspecialchars($u['id']) ?>">
                          <button type="submit" onclick="return confirm('Delete your account?');">Delete</button>
                        </form>
                        <?php
                      } else {
                        echo '<em>Cannot delete account</em>';
                      }
                    }
                  ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        
        <!-- Create new user: only visible if admin -->
        <div class="settings-item">
        <?php if ($isAdmin): ?>
          <h2>Create a New User</h2>
          <form method="POST" action="/admin">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <input type="hidden" name="action" value="create_user">

            <label for="new_username">Username</label><br>
            <input type="text" name="new_username" required><br>

            <label for="new_password">Password</label><br>
            <input type="password" name="new_password" required><br>

            <button type="submit">Create User</button>
          </form>
        <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Settings -->
    <section id="tab-settings" class="tab-content">
      <div class="center-container">
        <div class="title">
          <h1>Settings</h1>
        </div>
        <div class="settings-item">
          <label for="settings-dropdown">Style: not yet implemented</label>
          <p>ArchiveCMS<br>v1.0 PHP</p>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <p class="footertext">&copy; 2025 VAIO Library</p>
  </footer>

  <script src="/static/tabs.js"></script>
</body>
</html>