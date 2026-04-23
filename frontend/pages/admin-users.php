<?php
$basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header('Location: ' . $basePath . '/'); exit; }
$pageTitle = 'Admin — Users & Categories';
$currentPage = 'admin';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="dashboard-layout">
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <div class="dashboard-main">
        <h1>Admin Panel</h1>
        <div id="adminMessage" class="alert hidden"></div>

        <div class="page-header">
            <h2>Manage Users</h2>
        </div>
        <div class="table-container" id="users-table">
            <div class="loading-spinner">Loading users...</div>
        </div>

        <div class="page-header" style="margin-top: 2rem;">
            <h2>Manage Categories</h2>
        </div>

        <form id="categoryForm" class="post-form" style="margin-bottom: 1rem;">
            <div class="form-actions" style="margin-top: 0; align-items: end;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label for="categoryName">New Category Name</label>
                    <input type="text" id="categoryName" name="name" placeholder="e.g. AI, Marketing, Career" maxlength="100" required>
                </div>
                <button type="submit" class="btn btn-primary">+ Add Category</button>
            </div>
        </form>

        <div class="table-container" id="categories-table">
            <div class="loading-spinner">Loading categories...</div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
