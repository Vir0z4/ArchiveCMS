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

          <!-- Library Link -->
          <label for="library_link">Library Link</label><br>
          <label>
          <input type="checkbox" style="width: 0.5%;"
                name="box_settings[back_link][visible]" 
                <?= (!$page || (isset($page['box_settings']['back_link']['visible']) && $page['box_settings']['back_link']['visible'])) ? 'checked' : '' ?>>
            Show
          </label>
          <br>
          <input type="text" 
                name="box_settings[back_link][custom_text]" 
                placeholder="Leave empty"
                value="<?= isset($page['box_settings']['back_link']['custom_text']) ? htmlspecialchars($page['box_settings']['back_link']['custom_text']) : '' ?>">

        </div>

        <!-- Recovery Discs -->
        <div class="item">
          <h2>Recovery Discs</h2>
          <div class="box-settings">
            <label>
            <input type="checkbox" 
                  name="box_settings[recovery][visible]" 
                  <?= (!$page || (isset($page['box_settings']['recovery']['visible']) && $page['box_settings']['recovery']['visible'])) ? 'checked' : '' ?>>
              Show Box
            </label>
            <br>
            <label>Box Message</label>
            <textarea name="box_settings[recovery][message]" rows="3"><?= isset($page['box_settings']['recovery']['message']) ? htmlspecialchars($page['box_settings']['recovery']['message']) : 'These recovery discs are likely model locked. We are currently working on XP and lower support for Sony VAIO Recovery Patcher.' ?></textarea>
          </div>
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
          <div class="box-settings">
            <label>
            <input type="checkbox" 
                  name="box_settings[driver_packs][visible]" 
                  <?= (!$page || (isset($page['box_settings']['driver_packs']['visible']) && $page['box_settings']['driver_packs']['visible'])) ? 'checked' : '' ?>>
              Show Box
            </label>
            <br>
            <label>Box Message</label>
            <textarea name="box_settings[driver_packs][message]" rows="3"><?= isset($page['box_settings']['driver_packs']['message']) ? htmlspecialchars($page['box_settings']['driver_packs']['message']) : 'Windows XP and under recovery discs contain official driver packs on the last disc.' ?></textarea>
          </div>
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
          <div class="box-settings">
            <label>
            <input type="checkbox" 
                  name="box_settings[drivers][visible]" 
                  <?= (!$page || (isset($page['box_settings']['drivers']['visible']) && $page['box_settings']['drivers']['visible'])) ? 'checked' : '' ?>>
              Show Box
            </label>
            <br>
            <label>Box Message</label>
            <textarea name="box_settings[drivers][message]" rows="3"><?= isset($page['box_settings']['drivers']['message']) ? htmlspecialchars($page['box_settings']['drivers']['message']) : 'These are downloadable direct links to mirrored driver downloads.' ?></textarea>
          </div>
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
          <div class="box-settings">
            <label>
              <input type="checkbox" 
                    name="box_settings[broken_links][section_visible]" 
                    <?= (!$page || (isset($page['box_settings']['broken_links']['section_visible']) && $page['box_settings']['broken_links']['section_visible'])) ? 'checked' : '' ?>>
              Show Broken Links Section
            </label>
            <br>
            <label>
            <input type="checkbox" 
                  name="box_settings[broken_links][visible]" 
                  <?= (!$page || (isset($page['box_settings']['broken_links']['visible']) && $page['box_settings']['broken_links']['visible'])) ? 'checked' : '' ?>>
              Show Box
            </label>
            <br>
            <label>Box Message</label>
            <textarea name="box_settings[broken_links][message]" rows="3"><?= isset($page['box_settings']['broken_links']['message']) ? htmlspecialchars($page['box_settings']['broken_links']['message']) : 'These links are not directly downloadable, as no mirror of the download servers is available. For now, to download these files, you can copy the executable file name and search manually for it.' ?></textarea>
          </div>
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