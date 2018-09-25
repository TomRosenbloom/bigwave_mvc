<?php

/**
 * use singleton pattern to create a PDO database object
 *
 */
class Database {
    private $_connection;
    static $_instance;

    private function __construct($config)
    {
// make a config class that returns config from config.ini and pass that class in
// keep going in circles re paths, of autoloader etc. think I need a bootstrapping file...
// ...to set a global base path
// the conundrum is, need base path to find config file, but base path is a config var
// (should I just use .env?)

        try {
            $dsn = $config->DB_SOURCE . ":host="  . $config->DB_HOST . ';dbname=' . $config->DB_DATABASE;
            $this->_connection = new PDO($dsn, $config->DB_USERNAME, $config->DB_PASSWORD);
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    private function __clone() {}

    public static function getInstance($config)
    {
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self($config);
        }

        return self::$_instance;
    }

    public function getConnection()
    {
        return $this->_connection;
    }

}
