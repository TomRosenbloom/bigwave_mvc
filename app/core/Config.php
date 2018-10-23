<?php

namespace core;

/**
 * I'm using a config.ini file to store local config vars e.g. db creds
 * Non-sensitive stuff e.g. app_root and url_root is simply defined in this class
 * [as properties of this class, not constants - is that best?
 * It means that for e.g. the base view class will have to have a method to dig out the base url
 * Whilst we don't have a view class, and views are rendered just with the current v primitive
 * method in BaseController, a constant is much easier - because it avoids problems with
 * finding the Config class in the view - but something to come back to]
 *
 * For live deployment, will need some other way to store/retrieve db creds
 * [and ultimately, ought to use .env?]
 */
class Config
{
    static $_instance;
    protected $config = array();

    private function __construct()
    {
        define('APP_ROOT', dirname(dirname(__FILE__)));
        define('URL_ROOT', "http://localhost.bigwavemvc");
        define('SITE_NAME', "DIY MVC");

        $this->config = parse_ini_file(APP_ROOT . '\config\config.ini');

        define('DB_SOURCE', $this->DB_SOURCE);
        define('DB_HOST', $this->DB_HOST);
        define('DB_DATABASE', $this->DB_DATABASE);
        define('DB_USERNAME', $this->DB_USERNAME);
        define('DB_PASSWORD', $this->DB_PASSWORD);
    }

    private function __clone() {}

    public static function getInstance()
    {
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * get a config var from config array
     *
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        throw new \Exception('Invalid configuration option: ' . $key);
    }

    // static public function getSiteName()
    // {
    //     return $this->site_name;
    // }
}
