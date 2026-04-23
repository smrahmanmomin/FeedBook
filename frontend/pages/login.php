<?php
$basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';
if (isset($_SESSION['user_id'])) { header('Location: ' . $basePath . '/dashboard'); exit; }
$pageTitle = 'Login — FeedBook';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="auth-form">
    <h1>Welcome Back</h1>
    <p class="text-muted text-center">Sign in to your account</p>
    <div id="loginMessage" class="alert hidden"></div>
    <form id="loginForm">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="you@example.com" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="••••••" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>
    <p>Don't have an account? <a href="<?= $basePath ?>/register">Register here</a></p>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
