
<footer class="footer site-footer">
    <p>&copy; <?php echo date("Y"); ?> Prayer Time Reminder</p>
</footer>
</div>
<?php if (!empty($js)): ?>
        <?php foreach ($js as $jsFile): ?>
            <script src="<?= htmlspecialchars($jsFile) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
