<?php

/**
 * use singleton pattern to create a PDO database object
 *
 */
class Database {
    private $_connection;
    static $_instance;

    private function __construct()
    // passing in config object? That can't really be right. It's not like you will have a different config anywhere, right?
    // Remeber also: the only place this class will ever be used is in BaseModel...
    {
// make a config class that returns config from config.ini and pass that class in
// keep going in circles re paths, of autoloader etc. think I need a bootstrapping file...
// ...to set a global base path
// the conundrum is, need base path to find config file, but base path is a config var
// (should I just use .env?)
//
// NB i'm not storing PDO config anywhere separate, it's all hard-coded in here...

        try {
            $dsn = DB_SOURCE . ":host="  . DB_HOST . ';dbname=' . DB_DATABASE;
            $this->_connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    private function __clone() {}

    public static function getInstance()
    {
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getConnection()
    {
        return $this->_connection;
    }

}
