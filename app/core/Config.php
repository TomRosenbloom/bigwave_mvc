<?php

namespace core;

/**
 * I'm using a config.ini file to store local config vars e.g. db creds
 * Non-sensitive stuff e.g. app_root and url_root is simply defined in this class
 * [as properties of this class, not constants - is that best?
 * It means that for e.g. the base view class will have to have a method to dig out the base url]
 *
 * For live deployment, will need some other way to store/retrieve db creds
 * [and ultimately, ought to use .env?]
 */
class Config
{

    protected $config = array();

    protected $app_root; // physical root of app folder on server, e.g. xampp/htdocs/myMVC/app - for includes etc
    protected $url_root; // url root e.g. http://myMVC - for links in views etc.

    public function __construct()
    {
        $this->app_root = dirname(dirname(__FILE__));
        $this->url_root = "http://localhost.bigwavemvc";
        $this->config = parse_ini_file($this->app_root . '\config\config.ini'); // need to use a base_url type of thing
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

}
