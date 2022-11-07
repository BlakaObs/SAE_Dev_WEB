<?php

namespace sae\web\factory;

use PDO;

class ConnectionFactory
{
    static array $data;

    public static function setConfig(string $file): void
    {
        if (str_contains($file, ".ini")) {
            self::$data = parse_ini_file($file);
        }
    }

    public static function makeConnection(): PDO
    {
        $driver = self::$data['driver'];
        $host = self::$data['host'];
        $dbName = self::$data['database'];
        $dns = "$driver:host=$host; dbname=$dbName; charset=utf8";
        $username = self::$data['username'];
        $password = self::$data['password'];
        $base = new PDO($dns, $username, $password);
        return $base;
    }
}