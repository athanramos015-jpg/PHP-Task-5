<?php /* Expects: $pages, $page */ ?>
<nav aria-label="Main">
    <?php foreach ($pages as $slug => $info): ?>
        <a href="index.php?page=<?= e($slug) ?>"
           class="<?= $slug === $page ? 'active' : '' ?>"
           <?= $slug === $page ? 'aria-current="page"' : '' ?>><?= e($info['title']) ?></a>
    <?php endforeach; ?>
    <?php if (is_logged_in()): ?>
        <a href="index.php?page=logout">Logout</a>
    <?php endif; ?>
</nav>
