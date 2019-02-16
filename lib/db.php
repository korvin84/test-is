<?php

abstract class DB
{
    public static $db         = false;
    public        $last_error = false;

    public function __construct()
    {
        if (self::$db === false) {
            $this->connect();
        }
    }

    public function connect()
    {
        $dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST;
        try {
            self::$db = new PDO(
                $dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$db->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('FMPDOStatement', array()));
        } catch (PDOException $e) {
            $this->last_error = $e->getMessage();
        }
    }
}