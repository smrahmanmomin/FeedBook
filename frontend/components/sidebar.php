<aside class="sidebar">
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
        <a href="/feedbook/dashboard" class="sidebar-link <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="/feedbook/create-post" class="sidebar-link <?= ($currentPage ?? '') === 'create-post' ? 'active' : '' ?>">✏️ New Post</a>
        <a href="/feedbook/profile" class="sidebar-link <?= ($currentPage ?? '') === 'profile' ? 'active' : '' ?>">👤 Profile</a>
        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
            <a href="/feedbook/admin-users" class="sidebar-link <?= ($currentPage ?? '') === 'admin' ? 'active' : '' ?>">🛡️ Manage Users</a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-section">
        <h4>Categories</h4>
        <div id="sidebar-categories" class="sidebar-categories"></div>
    </div>
</aside>
