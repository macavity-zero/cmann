<?php

namespace DB;

use \Config\Database;
use \Exception;
use \Redis;

class MyRedis
{
    private static $implementation = null;
    private static $config = [];

    private static function implement()
    {
        self::$config = Database::$redis;
        if (!extension_loaded('redis')) {
            throw new Exception('php-redis module not installed');
        }
        self::$implementation = new Redis();
        self::$implementation->connect(self::$config['hostname'], self::$config['port']);
        self::$implementation->ping();
    }

    public static function SET(string $index, string $value):bool
    {
        !self::$implementation and self::implement();
        return self::$implementation->set('name', 'test');
    }

    public static function GET(string $index)
    {
        !self::$implementation and self::implement();
        if($index = self::$implementation->get('name'))
            return $index;
        else
            throw new Exception('no redis connection');
    }
}
