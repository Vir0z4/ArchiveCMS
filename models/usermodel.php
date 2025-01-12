<?php
// models/usermodel.php

require_once __DIR__ . '/../database/db.php';

function getUserByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch();
}

function getUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function listAllUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, username FROM users ORDER BY id ASC");
    return $stmt->fetchAll();
}

function createUser($username, $plainPassword) {
    global $pdo;
    if (getUserByUsername($username)) {
        return [false, "User '$username' already exists."];
    }
    $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:u, :h)");
    $ok   = $stmt->execute(['u' => $username, 'h' => $hash]);
    return $ok ? [true, null] : [false, "Could not create user."];
}

function deleteUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id LIMIT 1");
    return $stmt->execute(['id' => $id]);
}