<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prayer Time Reminder</title>
    <link rel="stylesheet" href="assets/css/style.css"> 
    <?php if (!empty($css)): ?>
        <?php foreach ($css as $cssFile): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($cssFile) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
<div class="site-content">
<nav class="navigation">
    <ul>
        <li><a href="<?= \Utils\Env::base_url('/'); ?>">Home</a></li>
        <li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= \Utils\Env::base_url('/signout'); ?>">Sign Out</a>
            <?php else: ?>
                <a href="<?= \Utils\Env::base_url('/subscriber'); ?>">Sign Up</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>
