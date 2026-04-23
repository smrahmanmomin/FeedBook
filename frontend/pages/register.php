<?php
$basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';
if (isset($_SESSION['user_id'])) { header('Location: ' . $basePath . '/dashboard'); exit; }
$pageTitle = 'Register — FeedBook';
?>
<?php include __DIR__ . '/../components/header.php'; ?>

<div class="auth-form">
    <h1>Create Account</h1>
    <p class="text-muted text-center">Join our community of writers</p>
    <div id="registerMessage" class="alert hidden"></div>
    <form id="registerForm">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" placeholder="John Doe" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="you@example.com" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Min 6 characters" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="••••••" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Create Account</button>
    </form>
    <p>Already have an account? <a href="<?= $basePath ?>/login">Sign in</a></p>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
