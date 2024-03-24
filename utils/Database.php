<?php
namespace Utils;

class Database {
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME');
            self::$instance = new \PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}
