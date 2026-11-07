<?php /* Expects: $pages, $page, $pageTitle */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> - PHP Output #2</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
    <div class="inner">
        <a class="brand" href="index.php">Saint Michael College of Caraga
            <small>IT/IS WEB - PHP Output #2</small></a>
        <?php require __DIR__ . '/nav.php'; ?>
    </div>
</header>
<main>
