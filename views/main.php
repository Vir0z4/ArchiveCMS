<!DOCTYPE html>
<html lang="en">

<!--
Copyright VAIO Library - ArchiveCMS v0.1
-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>VAIO Library Archive</title>
    <link rel="stylesheet" href="/static/style_main.css">
</head>
<body>
    <div class="center-container">
        <nav>
            <img src="/static/logo_main.png" alt="VAIO Library Logo" class="logo">
            <div class="menu">
                <a href="/search?q=PCG" class="menulink">PCG</a>
                <p class="dotlink">•</p>
                <a href="/search?q=PCV" class="menulink">PCV</a>
                <p class="dotlink">•</p>
                <a href="/search?q=VGN" class="menulink">VGN</a>
                <p class="dotlink">•</p>
                <a href="/search?q=VGC" class="menulink">VGC</a>
                <p class="dotlink">•</p>
                <a href="/search?q=VPC" class="menulink">VPC</a>
                <p class="dotlink">•</p>
                <a href="/search?q=SV" class="menulink">SV</a>
            </div>
        </nav>

        <div class="search-box">
            <form action="/search" method="GET">
                <input type="text" name="q" placeholder="Search...">
            </form>
          </div>

        <p id="prestext">The all-in-one drivers and recovery discs database.</p>
    </div>

    <footer>
        <div class="footermenu">
            <a href="https://vaiolibrary.com" target="_blank" class="footerlink">Library</a>
            <p class="footertext">•</p>
            <a href="https://download.vaiolibrary.com" class="footerlink">File Index</a>
            <p class="footertext">•</p>
            <a href="https://discord.gg/bF9BgTzqZk" target="_blank" class="footerlink">SonyPlaza</a>
            <p class="footertext">•</p>
            <a href="/about" class="footerlink">About</a>
            <p class="footertext">•</p>
            <p class="footertext">&copy; 2025 VAIO Library</p>
        </div>
    </footer>

    <script src="/static/field.js"></script>
</body>
</html>