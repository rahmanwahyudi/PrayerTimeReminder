<!-- views/subscriber/login.php -->
<div class="subscriber-signup-wrapper">
    <h2>Login to your account</h2>
    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form action="" method="POST" class="subscriber-signup-form">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="subscribe-button">Sign In</button>
        
    </form>
        <div class="already-member">
            Didn't have an account? <a href="<?= \Utils\Env::base_url('/subscriber'); ?>">Click here to Signup</a>.
        </div>
</div>
