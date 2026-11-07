<?php
/**
 * Creates one shared PDO connection.
 */
class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $c   = require __DIR__ . '/../config/database.php';
            $dsn = 'mysql:host=' . $c['host'] . ';dbname=' . $c['name'] . ';charset=' . $c['charset'];

            self::$connection = new PDO($dsn, $c['user'], $c['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$connection;
    }
}
