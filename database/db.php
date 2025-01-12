<?php
// database/db.php

require_once __DIR__ . '/../config.php';

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $pdoOptions);
} catch (PDOException $e) {
    die("Database connection failed. Please try again later.");
}