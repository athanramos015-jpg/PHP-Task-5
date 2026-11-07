<?php
/**
 * PHP Output #2 - front page that stitches the pages together
 * using require (functions, header, nav, page, footer).
 */
ob_start();          // allows header() redirects after output has started
session_start();

require __DIR__ . '/includes/functions.php';

$pages = [
    'home'     => ['title' => 'Home',            'file' => 'home.php'],
    'register' => ['title' => 'Register',        'file' => 'register.php'],
    'login'    => ['title' => 'Login',           'file' => 'login.php'],
    'forgot'   => ['title' => 'Forgot Password', 'file' => 'forgot.php'],
];

$page = $_GET['page'] ?? 'home';
if (!is_string($page)) {
    $page = 'home';
}

if ($page === 'logout') {
    $_SESSION = [];
    session_regenerate_id(true);
    header('Location: index.php?page=login&loggedout=1');
    exit;
}

if (!isset($pages[$page])) {
    $page = 'home';
}
$pageTitle = $pages[$page]['title'];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/pages/' . $pages[$page]['file'];
require __DIR__ . '/includes/footer.php';
