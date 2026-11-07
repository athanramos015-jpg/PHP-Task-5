<?php
/**
 * PHP Output #5 - Faculty CRUD (MVC front controller)
 *
 * URL pattern: index.php?action=index|create|store|edit|update|delete
 */
session_start();

require __DIR__ . '/core/Database.php';
require __DIR__ . '/app/helpers.php';
require __DIR__ . '/app/models/Faculty.php';
require __DIR__ . '/app/controllers/FacultyController.php';

$allowed = ['index', 'create', 'store', 'edit', 'update', 'delete'];
$action  = $_GET['action'] ?? 'index';
if (!is_string($action) || !in_array($action, $allowed, true)) {
    $action = 'index';
}

try {
    $controller = new FacultyController();
    $controller->$action();
} catch (PDOException $ex) {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Database error</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
    <main>
        <div class="card narrow">
            <h1>Database error</h1>
            <div class="alert danger">
                The page could not reach the database. Start MySQL, check <code>config/database.php</code>,
                and import <code>database/php_exercises.sql</code>.
            </div>
        </div>
    </main>
    </body>
    </html>
    <?php
}
