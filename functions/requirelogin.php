<?php
// functions/requirelogin.php

require_once __DIR__ . '/../config.php';

/**
 * Helper: check if user is logged in.
 */
function isLoggedIn() {
    return !empty($_SESSION['logged_in']);
}

/**
 * Helper: require login or redirect.
 */
function requireLogin() {
    if (empty($_SESSION['logged_in'])) {
        header('Location: /login');
        exit;
    }

    // If there's no stored login_time, treat as unauthenticated
    if (!isset($_SESSION['login_time'])) {
        session_destroy();
        header('Location: /login');
        exit;
    }

    $now = time();
    global $sessionLifetime;

    if (($now - $_SESSION['login_time']) > $sessionLifetime) {
        // Session is older than lifetime -> expire
        session_destroy();
        header('Location: /login?expired=1');
        exit;
    }
}