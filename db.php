<?php
class DB {

    private static $options = 
    [
        'serverName' => "localhost",
        'dbName' => "db",
        'userName' => "root",
        'pass' => "",
    ];

    static function connection(){
        try {
            $connection = new \PDO("mysql:host=".self::$options['serverName'].";dbname=".self::$options['dbName'].";charset=utf8", self::$options['userName'] , self::$options['pass']);
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $connection;
        }catch (\PDOException $exception){
            die($exception->getMessage());
        }
    }
}