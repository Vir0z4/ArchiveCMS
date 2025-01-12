<?php
// models/pagemodel.php

require_once __DIR__ . '/../database/db.php';

/**
 * Fetch a page by its slug.
 */
function getPageBySlug($slug) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = :slug LIMIT 1");
    $stmt->execute(['slug' => $slug]);
    return $stmt->fetch();
}

/**
 * List pages by status (Active or Inactive).
 */
function listPagesByStatus($status) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE status = :status");
    $stmt->execute(['status' => $status]);
    return $stmt->fetchAll();
}

/**
 * Create a new page (with sanitized data).
 */
function createPage($slug, $title, $description, $status, $recoveryDiscs, $driverPacks, $drivers, $brokenLinks) {
    global $pdo;

    // Check if slug already exists
    if (getPageBySlug($slug)) {
        return [false, "Slug '$slug' already exists. Choose a different slug."];
    }

    $sql = "INSERT INTO pages 
        (slug, title, description, status, last_modified, recovery_discs, driver_packs, drivers, broken_links)
        VALUES (:slug, :title, :description, :status, NOW(), :rd, :dp, :dr, :bl)";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        'slug'       => $slug,
        'title'      => $title,
        'description'=> $description,
        'status'     => $status,
        'rd'         => json_encode($recoveryDiscs),
        'dp'         => json_encode($driverPacks),
        'dr'         => json_encode($drivers),
        'bl'         => json_encode($brokenLinks)
    ]);
    return $success ? [true, null] : [false, "Failed to insert page."];
}

/**
 * Update an existing page.
 */
function updatePage($oldSlug, $newSlug, $title, $description, $status, $recoveryDiscs, $driverPacks, $drivers, $brokenLinks) {
    global $pdo;

    // Check old slug
    $oldPage = getPageBySlug($oldSlug);
    if (!$oldPage) {
        return [false, "Page with slug '$oldSlug' not found."];
    }

    // If slug changed, check if new slug is free
    if ($newSlug !== $oldSlug) {
        if (getPageBySlug($newSlug)) {
            return [false, "Slug '$newSlug' is already in use."];
        }
    }

    $sql = "UPDATE pages
            SET slug = :newSlug,
                title = :title,
                description = :description,
                status = :status,
                last_modified = NOW(),
                recovery_discs = :rd,
                driver_packs = :dp,
                drivers = :dr,
                broken_links = :bl
            WHERE slug = :oldSlug
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        'newSlug'    => $newSlug,
        'title'      => $title,
        'description'=> $description,
        'status'     => $status,
        'rd'         => json_encode($recoveryDiscs),
        'dp'         => json_encode($driverPacks),
        'dr'         => json_encode($drivers),
        'bl'         => json_encode($brokenLinks),
        'oldSlug'    => $oldSlug
    ]);

    return $success ? [true, null] : [false, "Failed to update page."];
}

/**
 * Load all pages (for searching).
 */
function loadAllPages() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM pages");
    $all = $stmt->fetchAll();
    // Re-index by slug if you want a dict-like array
    $dict = [];
    foreach ($all as $row) {
        $slug = $row['slug'];
        $dict[$slug] = $row;
    }
    return $dict;
}