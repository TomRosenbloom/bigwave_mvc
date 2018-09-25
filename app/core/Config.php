<?php

namespace core;

class Config
{

    protected $config = array();

    public function __construct()
    {
        $this->config = parse_ini_file('C:\xampp\htdocs\bigwave_mvc\app\config\config.ini'); // need to use a base_url type of thing
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        throw new \Exception('Invalid configuration option: ' . $key);
    }
}
