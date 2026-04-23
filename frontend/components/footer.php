</main>

<footer class="footer">
    <div class="container footer-content">
        <p>&copy; <?= date('Y') ?> <strong>FeedBook</strong> — Share Your Stories</p>
        <div class="footer-links">
            <a href="<?= $basePath ?>/">Home</a>
            <a href="<?= $basePath ?>/register">Join</a>
        </div>
    </div>
</footer>

<script>
window.BASE_PATH = <?= json_encode($basePath) ?>;
</script>
<?php $jsVersion = @filemtime(__DIR__ . '/../js/app.js') ?: time(); ?>
<script src="<?= $basePath ?>/frontend/js/app.js?v=<?= $jsVersion ?>"></script>
</body>
</html>
