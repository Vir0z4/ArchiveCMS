<?php
// config.php

$sessionLifetime = 2592000;                                 // 30 days in seconds
$sessionCookiePath = '/';                                   // Usually '/'
$sessionCookieDomain = 'localhost';                 // e.g. 'example.com'
$sessionCookieSecure = false;                               // true if using HTTPS
$sessionCookieHttpOnly = true;
$sessionCookieSameSite = 'Lax';                            // could be 'Strict', 'Lax', 'None' etc.
$sessionName = 'VAIO_SESSION';

$dbHost = 'localhost';
$dbName = 'archivecms_db';
$dbUser = 'archivecms_user';
$dbPass = 'testing123';

$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];