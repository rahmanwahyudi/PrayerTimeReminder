<!-- /views/subscriber/index.php -->
<div class="subscriber-signup-wrapper">
    <h2>Subscribe to Create Your Music Box</h2>
    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form action="" method="POST" class="subscriber-signup-form">
        <div class="form-group">
            <label for="subs_name">Name</label>
            <input type="text" id="subs_name" name="subs_name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone (optional)</label>
            <input type="tel" id="phone" name="phone">
        </div>
        <button type="submit" class="subscribe-button">Sign Up</button>
        
    </form>
        <div class="already-member">
            Already have an account? <a href="<?= \Utils\Env::base_url('/login'); ?>">Click here to login</a>.
        </div>
</div>
