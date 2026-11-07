<?php
/**
 * Database settings. Defaults match a fresh XAMPP install.
 * Change these if your MySQL username or password is different.
 */
const DB_HOST = 'localhost';
const DB_NAME = 'php_exercises';
const DB_USER = 'root';
const DB_PASS = '';

function db_connect(): PDO
{
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    return new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
}
