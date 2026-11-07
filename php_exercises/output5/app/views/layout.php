<?php /* Expects: $title, $content, $flash */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> - PHP Output #5</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="inner">
        <a class="brand" href="index.php">Saint Michael College of Caraga
            <small>IT/IS WEB - PHP Output #5 (Faculty CRUD, MVC)</small></a>
        <nav aria-label="Main">
            <a href="index.php?action=index">Faculty list</a>
            <a href="index.php?action=create">Add faculty</a>
        </nav>
    </div>
</header>
<main>
    <?php if (!empty($flash)): ?>
        <div class="alert <?= e($flash['type']) ?>" role="status"><?= e($flash['msg']) ?></div>
    <?php endif; ?>
    <?= $content ?>
</main>
<footer class="site-footer">IT/IS WEB - Web Systems and Technologies</footer>
</body>
</html>
