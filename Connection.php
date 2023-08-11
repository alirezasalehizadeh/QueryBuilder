<?php

namespace QueryBuilder;

class Connection
{
    private static $connection;

    private static $options =
    [
        'serverName' => "localhost",
        'dbName' => "",
        'userName' => "root",
        'password' => "",
    ];

    public static function getInstance()
    {
        if (!self::$connection) {
            self::$connection = self::connect();
        }
        return self::$connection;
    }

    private static function connect()
    {
        try {
            $connection = new \PDO("mysql:host=" . self::$options['serverName'] . ";dbname=" . self::$options['dbName'] . ";charset=utf8", self::$options['userName'], self::$options['password']);
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (\PDOException $exception) {
            die($exception->getMessage());
        }
    }
}
