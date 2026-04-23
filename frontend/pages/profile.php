<?php
$basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';
if (!isset($_SESSION['user_id'])) { header('Location: ' . $basePath . '/login'); exit; }
$pageTitle = 'Profile — FeedBook';
$currentPage = 'profile';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="dashboard-layout">
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <div class="dashboard-main">
        <h1>My Profile</h1>
        <div id="profileMessage" class="alert hidden"></div>
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar"><?= strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1)) ?></div>
                <div>
                    <h2><?= htmlspecialchars($_SESSION['name'] ?? '') ?></h2>
                    <p class="text-muted"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></p>
                    <span class="role-badge <?= $_SESSION['role'] ?? 'user' ?>"><?= ucfirst($_SESSION['role'] ?? 'user') ?></span>
                </div>
            </div>
            <form id="profileForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>

        <div class="my-posts-section">
            <h2>My Posts</h2>
            <div class="posts-grid" id="my-posts">
                <div class="loading-spinner">Loading...</div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
