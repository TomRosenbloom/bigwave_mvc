<?php

class Config
{

    protected $config = array();

    public function __construct()
    {
        $this->config = parse_ini_file('../config/config.ini');
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        throw new \Exception('Invalid configuration option: ' . $key);
    }
}
