<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header('Location: /feedbook/'); exit; }
$pageTitle = 'Admin — Manage Users';
$currentPage = 'admin';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="dashboard-layout">
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <div class="dashboard-main">
        <h1>Manage Users</h1>
        <div id="adminMessage" class="alert hidden"></div>
        <div class="table-container" id="users-table">
            <div class="loading-spinner">Loading users...</div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
