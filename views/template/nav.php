<nav class="navigation">
    <ul>
        <li><a href="/">Home</a></li>
        <li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="signout.php">Sign Out</a>
            <?php else: ?>
                <a href="signup.php">Sign Up</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>