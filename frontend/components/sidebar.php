<aside class="sidebar">
    <?php $basePath = $basePath ?? (defined('APP_BASE_PATH') ? APP_BASE_PATH : ''); ?>
    <div class="sidebar-section">
        <div class="sidebar-user">
            <div class="sidebar-avatar"><?= strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1)) ?></div>
            <div>
                <strong><?= htmlspecialchars($_SESSION['name'] ?? '') ?></strong>
                <span class="sidebar-role"><?= ucfirst($_SESSION['role'] ?? 'user') ?></span>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= $basePath ?>/dashboard" class="sidebar-link <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="<?= $basePath ?>/create-post" class="sidebar-link <?= ($currentPage ?? '') === 'create-post' ? 'active' : '' ?>">✏️ New Post</a>
        <a href="<?= $basePath ?>/profile" class="sidebar-link <?= ($currentPage ?? '') === 'profile' ? 'active' : '' ?>">👤 Profile</a>
        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
            <a href="<?= $basePath ?>/admin-users" class="sidebar-link <?= ($currentPage ?? '') === 'admin' ? 'active' : '' ?>">🛡️ Admin Panel</a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-section">
        <h4>Categories</h4>
        <div id="sidebar-categories" class="sidebar-categories"></div>
    </div>
</aside>
