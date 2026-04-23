<?php
$pageTitle = 'FeedBook — Share Your Stories';
$basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<!-- Hero -->
<section class="hero">
    <h1>Share Your Stories<br>With The World</h1>
    <p>A modern platform for writers, thinkers, and creators to publish articles and connect with readers.</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="hero-actions">
            <a href="<?= $basePath ?>/register" class="btn btn-white btn-large">Get Started</a>
            <a href="<?= $basePath ?>/login" class="btn btn-ghost btn-large">Sign In</a>
        </div>
    <?php else: ?>
        <a href="<?= $basePath ?>/create-post" class="btn btn-white btn-large">Write a Post</a>
    <?php endif; ?>
</section>

<!-- Category filter -->
<section class="category-filter" id="categoryFilter">
    <button class="cat-btn active" data-cat="">All</button>
</section>

<!-- All Posts -->
<section class="posts-section">
    <h2>Latest Posts</h2>
    <div class="posts-grid" id="homePosts">
        <div class="loading-spinner">Loading posts...</div>
    </div>
</section>

<?php if (!isset($_SESSION['user_id'])): ?>
<section class="features">
    <h2>Why FeedBook?</h2>
    <div class="features-grid">
        <div class="feature-card"><div class="feature-icon">✍️</div><h3>Easy Writing</h3><p>Beautiful editor to craft your stories.</p></div>
        <div class="feature-card"><div class="feature-icon">🌍</div><h3>Reach Readers</h3><p>Publish to a global audience instantly.</p></div>
        <div class="feature-card"><div class="feature-icon">🔒</div><h3>Secure</h3><p>Your content is protected with modern security.</p></div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/../components/footer.php'; ?>
