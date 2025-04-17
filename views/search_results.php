<!DOCTYPE html>
<html lang="en">

<!--
Copyright VAIO Library - ArchiveCMS v1.2
-->

<head>
  <meta charset="UTF-8">
  <title>Search - VAIO Library Archive</title>
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
      <?php if (!empty($matches)): ?>
        <h1>Found <?= count($matches) ?> results for "<?= htmlspecialchars($query) ?>"</h1>
        <div class="search-results">
          <br>
          <ul>
          <?php foreach ($matches as $p): ?>
            <li>
              <a href="/pages/<?= urlencode($p['slug']) ?>">
                <?= strtoupper(htmlspecialchars($p['title'])) ?>
              </a>
            </li>
          <?php endforeach; ?>
          </ul>
        </div>
      <?php else: ?>
        <h1>No results found for "<?= htmlspecialchars($query) ?>"</h1>
      <?php endif; ?>
    </div>
  </main>

  <footer>
    <p class="footertext">&copy; 2025 VAIO Library</p>
  </footer>
</body>
</html>