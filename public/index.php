<?php
// public/index.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions/csrf.php';
require_once __DIR__ . '/../functions/requirelogin.php';
require_once __DIR__ . '/../models/pagemodel.php';
require_once __DIR__ . '/../models/usermodel.php';

/**
 * Helper: render a view from /views.
 */
function render($viewName, array $vars = []) {
    extract($vars);
    include __DIR__ . '/../views/' . $viewName . '.php';
}

// Basic input sanitization function
function sanitizeString($value) {
    $allowedTags = '<b><strong><em><i><u><a><code><br>';
    return trim(strip_tags($value, $allowedTags));
}

// Basic path-based routing
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// -----------------------------
//  LOGIN / LOGOUT
// -----------------------------
if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}

// (2) Set session parameters BEFORE session_start().
ini_set('session.gc_maxlifetime', $sessionLifetime);
session_set_cookie_params([
    'lifetime' => $sessionLifetime,
    'path'     => $sessionCookiePath,
    'domain'   => $sessionCookieDomain,
    'secure'   => $sessionCookieSecure,
    'httponly' => $sessionCookieHttpOnly,
    'samesite' => $sessionCookieSameSite
]);
session_name($sessionName);

// (3) Start session AFTER setting the parameters
session_start();

if ($requestUri === '/login') {
    if ($method === 'POST') {
        // 1. Check CSRF
        if (!checkCSRFToken()) {
            http_response_code(403);
            $error = "Invalid CSRF token.";
            $csrfToken = generateCSRFToken(); 
            render('login', [
                'csrfToken' => $csrfToken,
                'error'     => $error
            ]);
            exit;
        }

        // 2. Get form data
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // 3. Check user
        $user = getUserByUsername($username);
        if ($user && password_verify($password, $user['password_hash'])) {
            // 4. Successful login
            session_regenerate_id(true);
            $csrfToken = generateCSRFToken();

            $_SESSION['logged_in']  = true;
            $_SESSION['username']   = $user['username'];
            $_SESSION['login_time'] = time();  // so we can do 30-day check

            header('Location: /admin');
            exit;
        }

        // If we reach here => invalid credentials
        http_response_code(401);
        $error = "Invalid username or password.";
        $csrfToken = generateCSRFToken();
        render('login', [
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
        exit;

    } else {
        // GET => show login form
        $csrfToken = generateCSRFToken();

        // Optionally check if session was expired
        $error = '';
        if (isset($_GET['expired'])) {
            $error = "Your session expired. Please log in again.";
        }

        render('login', [
            'csrfToken' => $csrfToken,
            'error'     => $error
        ]);
        exit;
    }
}

if ($requestUri === '/logout') {
    session_destroy();
    header('Location: /login');
    exit;
}

// -----------------------------
//  ADMIN PANEL
// -----------------------------
if ($requestUri === '/admin') {
    requireLogin();  // user must be logged in

    if ($method === 'POST') {
        if (!checkCSRFToken()) {
            http_response_code(403);
            echo "Invalid CSRF token.";
            exit;
        }

        $action = $_POST['action'] ?? '';

        if ($action === 'create_user') {
            // Only admin can create users
            if (($_SESSION['username'] ?? '') !== 'admin') {
                http_response_code(403);
                echo "You do not have permission to create users.";
                exit;
            }
            $newUsername = trim($_POST['new_username'] ?? '');
            $newPassword = trim($_POST['new_password'] ?? '');
            if ($newUsername === '' || $newPassword === '') {
                http_response_code(400);
                echo "Username or password cannot be empty.";
                exit;
            }
            [$ok, $err] = createUser($newUsername, $newPassword);
            if (!$ok) {
                http_response_code(400);
                echo "Server Error";
                exit;
            }
            header('Location: /admin');
            exit;

        } elseif ($action === 'delete_user') {
            $deleteId = $_POST['delete_user_id'] ?? null;
            if (!$deleteId) {
                http_response_code(400);
                echo "Missing user ID.";
                exit;
            }

            // Find user to delete
            $userToDelete = getUserById($deleteId);
            if (!$userToDelete) {
                http_response_code(404);
                echo "User not found.";
                exit;
            }

            $loggedInUser = $_SESSION['username'] ?? '';
            // Admin can delete anyone, EXCEPT cannot delete itself
            if ($loggedInUser === 'admin') {
                if ($userToDelete['username'] === 'admin') {
                    // admin is trying to delete "admin"
                    http_response_code(403);
                    echo "Admin cannot delete itself.";
                    exit;
                }
                // Otherwise, proceed
                deleteUserById($deleteId);
            } else {
                // Non-admin can only delete themselves
                if ($userToDelete['username'] === $loggedInUser) {
                    deleteUserById($deleteId);
                } else {
                    http_response_code(403);
                    echo "You can only delete your own account.";
                    exit;
                }
            }

            header('Location: /admin');
            exit;
        }

        // Unknown action
        http_response_code(400);
        echo "Unknown action.";
        exit;

    } else {
        // GET => Show admin panel
        $csrfToken      = generateCSRFToken();
        $activePages    = listPagesByStatus('Active');
        $inactivePages  = listPagesByStatus('Inactive');
        $users          = listAllUsers();
        render('admin_panel', [
            'csrfToken'      => $csrfToken,
            'active_pages'   => $activePages,
            'inactive_pages' => $inactivePages,
            'users'          => $users
        ]);
        exit;
    }
}

if ($requestUri === '/admin/pages/create') {
    requireLogin();
    if ($method === 'POST') {
        if (!checkCSRFToken()) {
            http_response_code(403);
            echo "Invalid CSRF token.";
            exit;
        }

        $allowedTags = '<b><strong><em><i><u><a><code><br>';

        $slug        = sanitizeString($_POST['slug'] ?? '');
        $title       = sanitizeString($_POST['title'] ?? '');
        $description = isset($_POST['description']) 
                    ? strip_tags(trim($_POST['description']), $allowedTags)
                    : '';
        $status      = ($_POST['status'] === 'Active') ? 'Active' : 'Inactive'; // safe-check

        // JSON fields
        $recovery_discs = json_decode($_POST['recovery_discs_json'] ?? '[]', true) ?: [];
        $driver_packs   = json_decode($_POST['driver_packs_json'] ?? '[]', true) ?: [];
        $drivers        = json_decode($_POST['drivers_json'] ?? '[]', true) ?: [];
        $broken_links   = json_decode($_POST['broken_links_json'] ?? '[]', true) ?: [];

        $boxSettings = [
            'back_link' => [
                'visible' => isset($_POST['box_settings']['back_link']['visible']),
                'custom_text' => $_POST['box_settings']['back_link']['custom_text'] ?? '',
            ],
            'recovery' => [
                'visible' => isset($_POST['box_settings']['recovery']['visible']),
                'message' => isset($_POST['box_settings']['recovery']['message']) 
                        ? strip_tags($_POST['box_settings']['recovery']['message'], '<b><strong><em><i><u><a><code>') 
                        : '',
            ],
            'driver_packs' => [
                'visible' => isset($_POST['box_settings']['driver_packs']['visible']),
                'message' => isset($_POST['box_settings']['driver_packs']['message']) 
                        ? strip_tags($_POST['box_settings']['driver_packs']['message'], '<b><strong><em><i><u><a><code>') 
                        : '',
            ],
            'drivers' => [
                'visible' => isset($_POST['box_settings']['drivers']['visible']),
                'message' => isset($_POST['box_settings']['drivers']['message']) 
                        ? strip_tags($_POST['box_settings']['drivers']['message'], '<b><strong><em><i><u><a><code>') 
                        : '',
            ],
            'broken_links' => [
                'section_visible' => isset($_POST['box_settings']['broken_links']['section_visible']),
                'visible' => isset($_POST['box_settings']['broken_links']['visible']),
                'message' => isset($_POST['box_settings']['broken_links']['message']) 
                        ? strip_tags($_POST['box_settings']['broken_links']['message'], '<b><strong><em><i><u><a><code>') 
                        : '',
            ]
        ];

        if (!$slug) {
            http_response_code(400);
            echo "Error: Slug cannot be empty.";
            exit;
        }
        if (!$title) {
            http_response_code(400);
            echo "Error: Title cannot be empty.";
            exit;
        }

        [$success, $err] = createPage($slug, $title, $description, $status, 
                                      $recovery_discs, $driver_packs, 
                                      $drivers, $broken_links, $boxSettings);
        if (!$success) {
            http_response_code(400);
            echo "Server Error";
            exit;
        }
        header('Location: /admin');
        exit;
    } else {
        // GET => Show create form
        $csrfToken = generateCSRFToken();
        render('page_form', ['page' => null, 'csrfToken' => $csrfToken]);
        exit;
    }
}

// Match route like /admin/pages/some-slug/edit
$patternEdit = '/^\/admin\/pages\/([^\/]+)\/edit$/';
if (preg_match($patternEdit, $requestUri, $matches)) {
    requireLogin();
    $oldSlug = $matches[1];
    $page = getPageBySlug($oldSlug);
    if (!$page) {
        http_response_code(404);
        echo "Page not found.";
        exit;
    }

    if ($method === 'POST') {
        if (!checkCSRFToken()) {
            http_response_code(403);
            echo "Invalid CSRF token.";
            exit;
        }

        $allowedTags = '<b><strong><em><i><u><a><code><br>';

        $newSlug     = sanitizeString($_POST['slug'] ?? $oldSlug);
        $title       = sanitizeString($_POST['title'] ?? $page['title']);
        $description = isset($_POST['description'])
                ? strip_tags(trim($_POST['description']), $allowedTags)
                : strip_tags($page['description'], $allowedTags);
        $status      = ($_POST['status'] === 'Active') ? 'Active' : 'Inactive';

        // JSON fields
        $recovery_discs = json_decode($_POST['recovery_discs_json'] ?? '[]', true) ?: [];
        $driver_packs   = json_decode($_POST['driver_packs_json'] ?? '[]', true) ?: [];
        $drivers        = json_decode($_POST['drivers_json'] ?? '[]', true) ?: [];
        $broken_links   = json_decode($_POST['broken_links_json'] ?? '[]', true) ?: [];

        $boxSettings = [
            'back_link' => [
                'visible' => isset($_POST['box_settings']['back_link']['visible']),
                'custom_text' => $_POST['box_settings']['back_link']['custom_text'] ?? '',
            ],
            'recovery' => [
                'visible' => isset($_POST['box_settings']['recovery']['visible']),
                'message' => $_POST['box_settings']['recovery']['message'] ?? '',
            ],
            'driver_packs' => [
                'visible' => isset($_POST['box_settings']['driver_packs']['visible']),
                'message' => $_POST['box_settings']['driver_packs']['message'] ?? '',
            ],
            'drivers' => [
                'visible' => isset($_POST['box_settings']['drivers']['visible']),
                'message' => $_POST['box_settings']['drivers']['message'] ?? '',
            ],
            'broken_links' => [
                'section_visible' => isset($_POST['box_settings']['broken_links']['section_visible']),
                'visible' => isset($_POST['box_settings']['broken_links']['visible']),
                'message' => $_POST['box_settings']['broken_links']['message'] ?? '',
            ]
        ];

        [$success, $err] = updatePage($oldSlug, $newSlug, $title, 
                                      $description, $status, 
                                      $recovery_discs, $driver_packs, 
                                      $drivers, $broken_links, $boxSettings);
        if (!$success) {
            http_response_code(400);
            echo "Server Error";
            exit;
        }
        header('Location: /admin');
        exit;
    } else {
        // GET => Show edit form
        // Decode JSON so the form can prefill
        $page['recovery_discs'] = json_decode($page['recovery_discs'], true) ?: [];
        $page['driver_packs']   = json_decode($page['driver_packs'], true)   ?: [];
        $page['drivers']        = json_decode($page['drivers'], true)        ?: [];
        $page['broken_links']   = json_decode($page['broken_links'], true)   ?: [];
        $page['box_settings']   = json_decode($page['box_settings'], true)   ?: [];

        $csrfToken = generateCSRFToken();
        render('page_form', ['page' => $page, 'csrfToken' => $csrfToken]);
        exit;
    }
}

// -----------------------------
//  PUBLIC ROUTES
// -----------------------------
if ($requestUri === '/') {
    render('main');
    exit;
}

if ($requestUri === '/about') {
    render('about');
    exit;
}

// /pages/some-slug
$patternPage = '/^\/pages\/([^\/]+)$/';
if (preg_match($patternPage, $requestUri, $matches)) {
    $slug = $matches[1];
    $page = getPageBySlug($slug);
    if (!$page) {
        http_response_code(404);
        echo "Page not found.";
        exit;
    }
    if ($page['status'] !== 'Active') {
        http_response_code(403);
        echo "Page is not active.";
        exit;
    }
    // Decode JSON fields
    $page['recovery_discs'] = json_decode($page['recovery_discs'], true) ?: [];
    $page['driver_packs']   = json_decode($page['driver_packs'], true)   ?: [];
    $page['drivers']        = json_decode($page['drivers'], true)        ?: [];
    $page['broken_links']   = json_decode($page['broken_links'], true)   ?: [];
    $page['box_settings']   = json_decode($page['box_settings'], true)   ?: [];

    render('public_page', ['page' => $page]);
    exit;
}

// /search?q=...
if ($requestUri === '/search') {
    $query = trim($_GET['q'] ?? '');
    if (!$query) {
        render('search_results', ['query' => '', 'matches' => []]);
        exit;
    }
    $queryLower = mb_strtolower($query);

    $pagesDict = loadAllPages();
    $allPages = array_values($pagesDict);

    // Check for exact matches
    $exactMatches = [];
    foreach ($allPages as $p) {
        if (mb_strtolower($p['title']) === $queryLower) {
            $exactMatches[] = $p;
        }
    }

    if (!empty($exactMatches)) {
        // Check if any pages start with the query (excluding exact matches)
        $prefixPages = [];
        foreach ($allPages as $p) {
            $lowerTitle = mb_strtolower($p['title']);
            if ($lowerTitle === $queryLower) continue; // Skip exact matches
            if (mb_strpos($lowerTitle, $queryLower) === 0) {
                $prefixPages[] = $p;
            }
        }

        if (!empty($prefixPages)) {
            // Show results (exact + prefix matches)
            $matches = array_merge($exactMatches, $prefixPages);
            render('search_results', ['query' => $query, 'matches' => $matches]);
            exit;
        } else {
            // No other prefixes → redirect to exact match
            header('Location: /pages/' . urlencode($exactMatches[0]['slug']));
            exit;
        }
    }

    // Check for pages where the query starts with their title (longest prefix)
    $prefixOfQuery = [];
    foreach ($allPages as $p) {
        $lowerTitle = mb_strtolower($p['title']);
        $titleLength = mb_strlen($lowerTitle);
        if ($titleLength === 0) continue;
        if (mb_strpos($queryLower, $lowerTitle) === 0) {
            $prefixOfQuery[] = $p;
        }
    }

    // Prioritize longest prefix
    if (!empty($prefixOfQuery)) {
        usort($prefixOfQuery, function ($a, $b) {
            return mb_strlen($b['title']) <=> mb_strlen($a['title']);
        });
        $longestMatch = $prefixOfQuery[0];
        header('Location: /pages/' . urlencode($longestMatch['slug']));
        exit;
    }

    // Fallback: Partial matches
    $matches = [];
    foreach ($allPages as $p) {
        if (mb_stripos($p['title'], $query) !== false) {
            $matches[] = $p;
        }
    }

    render('search_results', ['query' => $query, 'matches' => $matches]);
    exit;
}

// -----------------------------
//  NOT FOUND
// -----------------------------
http_response_code(404);
echo "404 Not Found";