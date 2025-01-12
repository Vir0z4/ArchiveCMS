<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($page['title']) ?> - VAIO Library Archive</title>
  <link rel="stylesheet" href="/static/style_page.css">
</head>
<body>
  <nav>
    <div class="nav-container">
      <div class="logo">
      <a href="/"><img src="/static/logo.png" alt="VAIO Library Logo"></a>
      </div>
      <div class="right-side">
        <div class="menu">
          <div class="dropdown">
            <a class="menulink">Series</a>
            <div class="submenu">
              <a href="/search?q=PCG">PCG</a>
              <a href="/search?q=PCV">PCV</a>
              <a href="/search?q=VGN">VGN</a>
              <a href="/search?q=VGC">VGC</a>
              <a href="/search?q=VPC">VPC</a>
              <a href="/search?q=SV">SV</a>
            </div>
          </div>
          <a href="https://download.vaiolibrary.com" class="menulink">File Index</a>
          <a href="https://vaiolibrary.com" target="_blank" class="menulink">Library</a>
          <a href="https://discord.gg/bF9BgTzqZk" target="_blank" class="menulink">SonyPlaza</a>
        </div>
        <div class="search-box">
          <form action="/search" method="GET">
            <input type="text" name="q" placeholder="Search...">
          </form>
        </div>
      </div>
    </div>
  </nav>

  <main>
    <div class="center-container">
      <div class="title">
        <h1><?= htmlspecialchars($page['title']) ?></h1>
        <p><?= htmlspecialchars($page['description']) ?></p>
        <p>Back to 
          <a href="https://vaiolibrary.com/<?= urlencode($page['title']) ?>">
            <?= htmlspecialchars($page['title']) ?>
          </a> 
          Library page.
        </p>
      </div>

      <!-- Recovery Discs -->
      <div class="item">
        <h2>Recovery Discs</h2>
        <div class="orange-box">
          <img src="https://archive.vaiolibrary.com/lib/plugins/wrap/images/note/48/important.png" alt="Icon">
          <p>These recovery discs are likely model locked. We are currently working on XP and lower support for Sony VAIO Recovery Patcher.</p>
        </div>
        <table>
          <thead>
            <tr>
              <th>Model</th>
              <th>Windows</th>
              <th>Link</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($page['recovery_discs'] as $disc): ?>
            <tr>
              <td><?= htmlspecialchars($disc['model']) ?></td>
              <td><?= htmlspecialchars($disc['windows']) ?></td>
              <td>
                <?php if (!empty($disc['link'])): ?>
                  <a href="<?= htmlspecialchars($disc['link']) ?>">
                    <?= htmlspecialchars($disc['link_text'] ?? 'Download') ?>
                  </a>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Third-party Driver Packs -->
      <div class="item">
        <h2>Third-party Driver Packs</h2>
        <div class="blue-box">
          <img src="https://archive.vaiolibrary.com/lib/plugins/wrap/images/note/48/info.png" alt="Icon">
          <p>Windows XP and under recovery discs contain official driver packs on the last disc.</p>
        </div>
        <table>
          <thead>
            <tr>
              <th>Model</th>
              <th>Description</th>
              <th>Link</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($page['driver_packs'] as $pack): ?>
            <tr>
              <td><?= htmlspecialchars($pack['model']) ?></td>
              <td><?= htmlspecialchars($pack['description']) ?></td>
              <td>
                <?php if (!empty($pack['link'])): ?>
                  <a href="<?= htmlspecialchars($pack['link']) ?>">
                    <?= htmlspecialchars($pack['link_text'] ?? 'Download') ?>
                  </a>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Drivers -->
      <div class="item">
        <h2>Drivers</h2>
        <div class="green-box">
          <img src="https://archive.vaiolibrary.com/lib/plugins/wrap/images/note/48/download.png" alt="Icon">
          <p>These are downloadable direct links to mirrored driver downloads.</p>
        </div>
        <table>
          <thead>
            <tr>
              <th>Type</th>
              <th>Description</th>
              <th>Link</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($page['drivers'] as $drv): ?>
            <tr>
              <td><?= htmlspecialchars($drv['type']) ?></td>
              <td><?= htmlspecialchars($drv['description']) ?></td>
              <td>
                <?php if (!empty($drv['link'])): ?>
                  <a href="<?= htmlspecialchars($drv['link']) ?>">
                    <?= htmlspecialchars($drv['link_text'] ?? 'Download') ?>
                  </a>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Broken Links -->
      <div class="item">
        <h2>Broken Links</h2>
        <div class="red-box">
          <img src="https://archive.vaiolibrary.com/lib/plugins/wrap/images/note/48/alert.png" alt="Icon">
          <p>These links are not directly downloadable, as no mirror of the download servers is available. For now, to download these files, you can copy the executable file name and search manually for it.</p>
        </div>
        <table>
          <thead>
            <tr>
              <th>Type</th>
              <th>Description</th>
              <th>EXE</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($page['broken_links'] as $broken): ?>
            <tr>
              <td><?= htmlspecialchars($broken['type']) ?></td>
              <td><?= htmlspecialchars($broken['description']) ?></td>
              <td><?= htmlspecialchars($broken['exe'] ?? 'N/A') ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <br>
      </div>
    </div>
  </main>

  <footer>
    <p class="footertext">&copy; 2025 VAIO Library</p>
  </footer>
</body>
</html>