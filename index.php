<?php
session_start();
require_once __DIR__ . '/backend/config/config.php';

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$base_path = ($script_dir === '/' || $script_dir === '.') ? '' : rtrim($script_dir, '/');
if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', $base_path);
}

$route_path = $request_uri;
if ($base_path !== '' && strpos($request_uri, $base_path) === 0) {
    $route_path = substr($request_uri, strlen($base_path));
}
$route = trim($route_path, '/');

// --- PAGE ROUTES ---
if (empty($route)) {
    include __DIR__ . '/frontend/pages/home.php';
} elseif ($route === 'login') {
    include __DIR__ . '/frontend/pages/login.php';
} elseif ($route === 'register') {
    include __DIR__ . '/frontend/pages/register.php';
} elseif ($route === 'dashboard') {
    include __DIR__ . '/frontend/pages/dashboard.php';
} elseif ($route === 'profile') {
    include __DIR__ . '/frontend/pages/profile.php';
} elseif ($route === 'create-post') {
    include __DIR__ . '/frontend/pages/create-post.php';
} elseif ($route === 'edit-post') {
    include __DIR__ . '/frontend/pages/edit-post.php';
} elseif (preg_match('/^post\/(\d+)$/', $route, $m)) {
    $_GET['id'] = $m[1];
    include __DIR__ . '/frontend/pages/single-post.php';
} elseif ($route === 'admin-users') {
    include __DIR__ . '/frontend/pages/admin-users.php';
// --- API ROUTES ---
} elseif (strpos($route, 'api/') === 0) {
    $api_route = str_replace('api/', '', $route);
    $api_file = __DIR__ . '/backend/api/' . $api_route . '.php';
    if (file_exists($api_file)) {
        include $api_file;
    } else {
        header('HTTP/1.1 404 Not Found');
        header('Content-Type: application/json');
        echo json_encode(['error' => 'API endpoint not found']);
    }
} else {
    include __DIR__ . '/frontend/pages/home.php';
}
?>
