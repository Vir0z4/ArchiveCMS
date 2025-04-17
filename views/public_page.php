<!DOCTYPE html>
<html lang="en">

<!--
Copyright VAIO Library - ArchiveCMS v1.2
-->

<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($page['title']) ?> - VAIO Library Archive</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
  <link rel="stylesheet" href="/static/style_page.css">
</head>
<body>
  <nav>
    <div class="nav-container">
      <input type="checkbox" id="mobile-menu-toggle" class="mobile-menu-toggle">
      <div class="logo">
        <a href="/"><img src="/static/logo.png" alt="VAIO Library Logo"></a>
      </div>
      <label for="mobile-menu-toggle" class="hamburger">☰</label>
      <label for="mobile-menu-toggle" class="overlay"></label>
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
        <?php if (!empty($page['box_settings']['back_link']['visible'])): ?>
          <p>
            <?= !empty($page['box_settings']['back_link']['custom_text'])
                ? htmlspecialchars($page['box_settings']['back_link']['custom_text'])
                : 'Back to <a href="https://vaiolibrary.com/'.urlencode($page['title']).'">'.htmlspecialchars($page['title']).'</a> Library page.' 
            ?>
          </p>
        <?php endif; ?>
      </div>

      <!-- Recovery Discs -->
      <div class="item">
        <h2>Recovery Discs</h2>
        <?php if (!empty($page['box_settings']['recovery']['visible'])): ?>
          <div class="orange-box">
            <img src="/static/important.png" alt="Icon">
            <p><?= htmlspecialchars_decode($page['box_settings']['recovery']['message']) ?></p>
          </div>
        <?php endif; ?>
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
                <?php if (!empty($disc['links'])): ?>
                  <?php foreach ($disc['links'] as $index => $link): ?>
                    <?php if ($index > 0): ?> - <?php endif; ?>
                    <a href="<?= htmlspecialchars($link['url']) ?>">
                      <?= htmlspecialchars($link['text'] ?? 'Download') ?>
                    </a>
                  <?php endforeach; ?>
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
        <?php if (!empty($page['box_settings']['driver_packs']['visible'])): ?>
          <div class="blue-box">
            <img src="/static/info.png" alt="Icon">
            <p><?= htmlspecialchars_decode($page['box_settings']['driver_packs']['message']) ?></p>
          </div>
        <?php endif; ?>
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
                <?php if (!empty($pack['links'])): ?>
                  <?php foreach ($pack['links'] as $index => $link): ?>
                    <?php if ($index > 0): ?> - <?php endif; ?>
                    <a href="<?= htmlspecialchars($link['url']) ?>">
                      <?= htmlspecialchars($link['text'] ?? 'Download') ?>
                    </a>
                  <?php endforeach; ?>
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
        <?php if (!empty($page['box_settings']['drivers']['visible'])): ?>
          <div class="green-box">
            <img src="/static/download.png" alt="Icon">
            <p><?= htmlspecialchars_decode($page['box_settings']['drivers']['message']) ?></p>
          </div>
        <?php endif; ?>
        <table>
          <thead>
            <tr>
              <th>Windows</th>
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
                <?php if (!empty($drv['links'])): ?>
                  <?php foreach ($drv['links'] as $index => $link): ?>
                    <?php if ($index > 0): ?> - <?php endif; ?>
                    <a href="<?= htmlspecialchars($link['url']) ?>">
                      <?= htmlspecialchars($link['text'] ?? 'Download') ?>
                    </a>
                  <?php endforeach; ?>
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
      <?php if (!empty($page['box_settings']['broken_links']['section_visible'])): ?>
      <div class="item">
        <h2>Broken Links</h2>
        <?php if (!empty($page['box_settings']['broken_links']['visible'])): ?>
          <div class="red-box">
            <img src="/static/alert.png" alt="Icon">
            <p><?= htmlspecialchars_decode($page['box_settings']['broken_links']['message']) ?></p>
          </div>
        <?php endif; ?>
        <table>
          <thead>
            <tr>
              <th>Windows</th>
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
      <?php endif; ?>
    </div>
  </main>

  <footer>
    <p class="footertext">&copy; 2025 VAIO Library</p>
  </footer>
</body>
</html>