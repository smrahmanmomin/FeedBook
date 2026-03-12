<?php
session_start();
require_once __DIR__ . '/backend/config/config.php';

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base_path = '/feedbook';
$route = trim(str_replace($base_path, '', $request_uri), '/');

// --- PAGE ROUTES ---
if (empty($route)) {
    include __DIR__ . '/frontend/pages/home.php';
} elseif ($route === 'login') {
    include __DIR__ . '/frontend/pages/login.php';
} elseif ($route === 'register') {
    include __DIR__ . '/frontend/pages/register.php';
} elseif ($route === 'dashboard' && isset($_SESSION['user_id'])) {
    include __DIR__ . '/frontend/pages/dashboard.php';
} elseif ($route === 'profile' && isset($_SESSION['user_id'])) {
    include __DIR__ . '/frontend/pages/profile.php';
} elseif ($route === 'create-post' && isset($_SESSION['user_id'])) {
    include __DIR__ . '/frontend/pages/create-post.php';
} elseif ($route === 'edit-post' && isset($_SESSION['user_id'])) {
    include __DIR__ . '/frontend/pages/edit-post.php';
} elseif (preg_match('/^post\/(\d+)$/', $route, $m)) {
    $_GET['id'] = $m[1];
    include __DIR__ . '/frontend/pages/single-post.php';
} elseif ($route === 'admin-users' && isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'admin') {
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
