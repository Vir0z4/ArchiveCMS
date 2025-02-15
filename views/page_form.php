<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Administration - VAIO Library Archive</title>
  <link rel="stylesheet" href="/static/style_admin.css" />
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
    <div class="tab-button" onclick="window.location.href='/admin'">Back</div>
  </div>

  <main>
    <div class="center-container">
      <div class="title">
        <h1><?= $page ? "Edit" : "Create" ?> Page</h1>
      </div>

      <form id="page-form" method="POST">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div class="settings-item">

          <!-- Slug -->
          <label for="slug">Page URL (Slug)</label><br>
          <input type="text" id="slug" name="slug" placeholder="e.g. vgn-z" value="<?= $page ? htmlspecialchars($page['slug']) : '' ?>">
          <br><br>

          <!-- Title -->
          <label for="title">Title</label><br>
          <input type="text" id="title" name="title" placeholder="e.g. VGN-Z" value="<?= $page ? htmlspecialchars($page['title']) : '' ?>">
          <br><br>

          <!-- Description -->
          <label for="description">Description</label><br>
          <textarea id="description" name="description" rows="3" cols="70" placeholder="Leave empty"><?= $page ? htmlspecialchars($page['description']) : '' ?></textarea>
          <br><br>

          <!-- Status -->
          <?php $selectedStatus = $page ? $page['status'] : 'Active'; ?>
            <label for="status">Status</label><br>
            <select id="status" name="status">
              <option value="Active"   <?= ($selectedStatus === 'Active') ? 'selected' : '' ?>>Active</option>
              <option value="Inactive" <?= ($selectedStatus === 'Inactive') ? 'selected' : '' ?>>Inactive</option>
            </select>
          <br><br>

        </div>

        <!-- Recovery Discs -->
        <div class="item">
          <h2>Recovery Discs</h2>
          <table id="recovery-discs-table">
            <thead>
              <tr>
                <th>Model</th>
                <th>Windows</th>
                <th>Link</th>
                <th>Link Text</th>
                <th>Remove</th>
              </tr>
            </thead>
            <tbody>
            <?php if ($page && !empty($page['recovery_discs'])): 
                  foreach ($page['recovery_discs'] as $disc): ?>
              <tr>
                <td><input type="text" value="<?= htmlspecialchars($disc['model'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($disc['windows'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($disc['link'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($disc['link_text'] ?? '') ?>"></td>
                <td><button type="button" class="remove-row-btn">Remove</button></td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
          <button type="button" id="add-recovery-disc">Add Recovery Disc</button>
        </div>
        <br><br>

        <!-- Driver Packs -->
        <div class="item">
          <h2>Driver Packs</h2>
          <table id="driver-packs-table">
            <thead>
              <tr>
                <th>Model</th>
                <th>Description</th>
                <th>Link</th>
                <th>Link Text</th>
                <th>Remove</th>
              </tr>
            </thead>
            <tbody>
            <?php if ($page && !empty($page['driver_packs'])):
                  foreach ($page['driver_packs'] as $pack): ?>
              <tr>
                <td><input type="text" value="<?= htmlspecialchars($pack['model'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($pack['description'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($pack['link'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($pack['link_text'] ?? '') ?>"></td>
                <td><button type="button" class="remove-row-btn">Remove</button></td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
          <button type="button" id="add-driver-pack">Add Driver Pack</button>
        </div>
        <br><br>

        <!-- Drivers -->
        <div class="item">
          <h2>Drivers</h2>
          <table id="drivers-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Description</th>
                <th>Link</th>
                <th>Link Text</th>
                <th>Remove</th>
              </tr>
            </thead>
            <tbody>
            <?php if ($page && !empty($page['drivers'])):
                  foreach ($page['drivers'] as $drv): ?>
              <tr>
                <td><input type="text" value="<?= htmlspecialchars($drv['type'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($drv['description'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($drv['link'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($drv['link_text'] ?? '') ?>"></td>
                <td><button type="button" class="remove-row-btn">Remove</button></td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
          <button type="button" id="add-driver">Add Driver</button>
        </div>
        <br><br>

        <!-- Broken Links -->
        <div class="item">
          <h2>Broken Links</h2>
          <table id="broken-links-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Description</th>
                <th>EXE</th>
                <th>Remove</th>
              </tr>
            </thead>
            <tbody>
            <?php if ($page && !empty($page['broken_links'])):
                  foreach ($page['broken_links'] as $broken): ?>
              <tr>
                <td><input type="text" value="<?= htmlspecialchars($broken['type'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($broken['description'] ?? '') ?>"></td>
                <td><input type="text" value="<?= htmlspecialchars($broken['exe'] ?? '') ?>"></td>
                <td><button type="button" class="remove-row-btn">Remove</button></td>
              </tr>
            <?php endforeach; endif; ?>
            </tbody>
          </table>
          <button type="button" id="add-broken-link">Add Broken Link</button>
        </div>

        <textarea name="recovery_discs_json" id="recovery_discs_json" hidden></textarea>
        <textarea name="driver_packs_json" id="driver_packs_json" hidden></textarea>
        <textarea name="drivers_json" id="drivers_json" hidden></textarea>
        <textarea name="broken_links_json" id="broken_links_json" hidden></textarea>

        <br><br>
        <button type="submit"><?= $page ? "Update" : "Create" ?></button>
      </form>
    </div>
  </main>

  <footer>
    <p class="footertext">&copy; 2025 VAIO Library</p>
  </footer>

  <script src="/static/editor.js"></script>
</body>
</html>