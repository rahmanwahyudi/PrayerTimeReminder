<!-- views/subscriber/success.php -->
<div class="signup-success-wrapper">
    <h2><?= htmlspecialchars($success) ?></h2>
    <p>Welcome, <?= htmlspecialchars($name) ?>! Your account has been successfully created.</p>
    <a href="<?= \Utils\Env::base_url('/login'); ?>" class="subscribe-button">Sign In</a>
</div>
