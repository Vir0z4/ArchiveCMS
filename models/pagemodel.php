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
function createPage($slug, $title, $description, $status, $recoveryDiscs, $driverPacks, $drivers, $brokenLinks, $boxSettings) {
    global $pdo;

    // Validate recovery discs structure
    foreach ($recoveryDiscs as &$disc) {
        if (!isset($disc['links']) || !is_array($disc['links'])) {
            $disc['links'] = [];
        }
        // Remove legacy fields if present
        unset($disc['link'], $disc['link_text']);
    }

    // Validate driver packs structure
    foreach ($driverPacks as &$pack) {
        if (!isset($pack['links']) || !is_array($pack['links'])) {
            $pack['links'] = [];
        }
        unset($pack['link'], $pack['link_text']);
    }

    // Validate drivers structure
    foreach ($drivers as &$driver) {
        if (!isset($driver['links']) || !is_array($driver['links'])) {
            $driver['links'] = [];
        }
        unset($driver['link'], $driver['link_text']);
    }

    $sql = "INSERT INTO pages 
        (slug, title, description, status, last_modified, recovery_discs, driver_packs, drivers, broken_links, box_settings)
        VALUES (:slug, :title, :description, :status, NOW(), :rd, :dp, :dr, :bl, :bs)";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        'slug'        => $slug,
        'title'       => $title,
        'description' => $description,
        'status'      => $status,
        'rd'          => json_encode($recoveryDiscs),
        'dp'          => json_encode($driverPacks),
        'dr'          => json_encode($drivers),
        'bl'          => json_encode($brokenLinks),
        'bs'          => json_encode($boxSettings)
    ]);
    return $success ? [true, null] : [false, "Failed to insert page."];
}

function updatePage($oldSlug, $newSlug, $title, $description, $status, $recoveryDiscs, $driverPacks, $drivers, $brokenLinks, $boxSettings) {
    global $pdo;

    // Validate and normalize recovery discs data
    foreach ($recoveryDiscs as &$disc) {
        if (!isset($disc['links']) || !is_array($disc['links'])) {
            $disc['links'] = [];
        }
        unset($disc['link'], $disc['link_text']);
        
        // Ensure minimum link structure
        foreach ($disc['links'] as &$link) {
            if (!isset($link['url'])) $link['url'] = '';
            if (!isset($link['text'])) $link['text'] = '';
        }
    }

    // Validate driver packs
    foreach ($driverPacks as &$pack) {
        if (!isset($pack['links']) || !is_array($pack['links'])) {
            $pack['links'] = [];
        }
        unset($pack['link'], $pack['link_text']);
        
        foreach ($pack['links'] as &$link) {
            if (!isset($link['url'])) $link['url'] = '';
            if (!isset($link['text'])) $link['text'] = '';
        }
    }

    // Validate drivers
    foreach ($drivers as &$driver) {
        if (!isset($driver['links']) || !is_array($driver['links'])) {
            $driver['links'] = [];
        }
        unset($driver['link'], $driver['link_text']);
        
        foreach ($driver['links'] as &$link) {
            if (!isset($link['url'])) $link['url'] = '';
            if (!isset($link['text'])) $link['text'] = '';
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
        broken_links = :bl,
        box_settings = :bs
    WHERE slug = :oldSlug
    LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        'newSlug'     => $newSlug,
        'title'       => $title,
        'description' => $description,
        'status'      => $status,
        'rd'          => json_encode($recoveryDiscs),
        'dp'          => json_encode($driverPacks),
        'dr'          => json_encode($drivers),
        'bl'          => json_encode($brokenLinks),
        'bs'          => json_encode($boxSettings),
        'oldSlug'     => $oldSlug
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