<?php
$basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';
if (!isset($_SESSION['user_id'])) { header('Location: ' . $basePath . '/login'); exit; }
$pageTitle = 'Dashboard — FeedBook';
$currentPage = 'dashboard';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="dashboard-layout">
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <div class="dashboard-main">
        <div class="page-header">
            <h1>Dashboard</h1>
            <a href="<?= $basePath ?>/create-post" class="btn btn-primary">+ New Post</a>
        </div>
        <p class="welcome-text">Welcome back, <strong><?= htmlspecialchars($_SESSION['name'] ?? '') ?></strong>! 👋</p>
        <div id="dashboardMessage" class="alert hidden"></div>
        <div class="posts-grid" id="posts-container">
            <div class="loading-spinner">Loading your posts...</div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
